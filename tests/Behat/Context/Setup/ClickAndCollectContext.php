<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context\Setup;

use App\ClickAndCollect\Entity\PickupPoint;
use Behat\Behat\Context\Context;
use Behat\Step\Given;
use Behat\Step\Then;
use Behat\Step\When;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ShippingMethodExampleFactory;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Webmozart\Assert\Assert;
use Behat\MinkExtension\Context\RawMinkContext;

final class ClickAndCollectContext extends RawMinkContext implements Context
{
    public function __construct(
        private readonly SharedStorageInterface $sharedStorage,
        private readonly ShippingMethodRepositoryInterface $shippingMethodRepository,
        private readonly ShippingMethodExampleFactory $shippingMethodExampleFactory,
        private readonly EntityManagerInterface $entityManager,
        private readonly CartContextInterface $cartContext,
    ) {
    }

    #[Given('/^the store offers a "([^"]+)" shipping method with ("[^"]+") fee and free above ("[^"]+")$/')]
    public function theStoreOffersAClickAndCollectShippingMethod(string $name, int $fee, int $freeAbove): void
    {
        $channel = $this->sharedStorage->get('channel');

        $this->saveShippingMethod($this->shippingMethodExampleFactory->create([
            'name' => $name,
            'enabled' => true,
            'zone' => $this->getShippingZone(),
            'calculator' => [
                'type' => 'click_and_collect',
                'configuration' => [
                    $channel->getCode() => [
                        'handling_fee' => $fee,
                        'free_above' => $freeAbove,
                    ],
                ],
            ],
            'channels' => [$channel],
        ]));
    }

    #[Given('/^this shipping method has pickup points named "([^"]+)" and "([^"]+)"$/')]
    public function thisShippingMethodHasPickupPointsNamed(string $firstPointName, string $secondPointName): void
    {
        /** @var ShippingMethodInterface $shippingMethod */
        $shippingMethod = $this->sharedStorage->get('shipping_method');

        foreach ([$firstPointName, $secondPointName] as $name) {
            $pickupPoint = new PickupPoint();
            $pickupPoint->setName($name);

            $code = strtolower(str_replace(' ', '_', $name));
            $pickupPoint->setCode($code);

            $pickupPoint->addShippingMethod($shippingMethod);

            $this->entityManager->persist($pickupPoint);
        }

        $this->entityManager->flush();
    }

    #[When('/^I select "([^"]+)" as my pickup point$/')]
    public function iSelectAsMyPickupPoint(string $pointName): void
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();
        Assert::isInstanceOf($cart, OrderInterface::class, 'Current cart is invalid.');

        /** @var ShipmentInterface $shipment */
        $shipment = $cart->getShipments()->first();
        Assert::notNull($shipment, 'No shipment found for the current cart.');

        $pickupPoint = $this->entityManager->getRepository(PickupPoint::class)->findOneBy(['name' => $pointName]);
        Assert::notNull($pickupPoint, sprintf('Pickup point named "%s" was not found in the database.', $pointName));

        $shipment->setPickupPoint($pickupPoint);

        $this->entityManager->persist($shipment);
        $this->entityManager->flush();
    }

    #[Then('/^my shipment pickup point should be "([^"]+)"$/')]
    public function myShipmentPickupPointShouldBe(string $expectedPointName): void
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        /** @var ShipmentInterface $shipment */
        $shipment = $cart->getShipments()->first();

        Assert::notNull($shipment, 'No shipment found for the current cart.');

        $pickupPoint = $shipment->getPickupPoint();

        Assert::notNull($pickupPoint, 'Shipment does not have any pickup point assigned.');
        Assert::same(
            $pickupPoint->getName(),
            $expectedPointName,
            sprintf('Expected pickup point to be "%s", but got "%s".', $expectedPointName, $pickupPoint->getName())
        );
    }

    private function saveShippingMethod(ShippingMethodInterface $shippingMethod): void
    {
        $this->shippingMethodRepository->add($shippingMethod);
        $this->sharedStorage->set('shipping_method', $shippingMethod);
    }

    private function getShippingZone(): ZoneInterface
    {
        if ($this->sharedStorage->has('shipping_zone')) {
            return $this->sharedStorage->get('shipping_zone');
        }

        return $this->sharedStorage->get('zone');
    }
}
