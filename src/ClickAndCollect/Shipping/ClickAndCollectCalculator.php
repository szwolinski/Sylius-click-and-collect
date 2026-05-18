<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Shipping;

use Sylius\Bundle\ShippingBundle\Attribute\AsShippingCalculator;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Webmozart\Assert\Assert;

#[AsShippingCalculator(
    calculator: self::TYPE,
    label: 'sylius.form.shipping_calculator.click_and_collect.label',
    formType: ClickAndCollectConfigurationType::class
)]
final class ClickAndCollectCalculator implements CalculatorInterface
{
    private const string TYPE = 'click_and_collect';

    /**
     * @param array<string, mixed> $configuration
     */
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        Assert::isInstanceOf($subject, \Sylius\Component\Core\Model\ShipmentInterface::class);

        /** @var ShipmentInterface $subject */
        /** @var OrderInterface $order */
        $order = $subject->getOrder();
        $channelCode = $order->getChannel()->getCode();

        if (!isset($configuration[$channelCode])) {
            throw new MissingChannelConfigurationException(
                sprintf('"Click & Collect" calculator is not configured for "%s" channel.', $channelCode)
            );
        }

        $channelConfig = $configuration[$channelCode];
        $handlingFee = (int) ($channelConfig['handling_fee'] ?? 0);
        $freeAbove = $channelConfig['free_above'] ?? null;

        if ($handlingFee === 0) {
            return 0;
        }

        if ($freeAbove !== null && $order->getItemsTotal() >= $freeAbove) {
            return 0;
        }

        return $handlingFee;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
