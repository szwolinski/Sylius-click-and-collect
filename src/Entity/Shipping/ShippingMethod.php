<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use App\ClickAndCollect\Entity\PickupPoint;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;
use Sylius\Component\Shipping\Model\ShippingMethodTranslationInterface;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_shipping_method')]
class ShippingMethod extends BaseShippingMethod
{
    #[ORM\ManyToMany(targetEntity: PickupPoint::class, mappedBy: 'shippingMethods')]
    protected Collection $pickupPoints;

    public function __construct()
    {
        parent::__construct();
        $this->pickupPoints = new ArrayCollection();
    }

    /**
     * @return Collection<int, PickupPoint>
     */
    public function getPickupPoints(): Collection
    {
        return $this->pickupPoints;
    }

    protected function createTranslation(): ShippingMethodTranslationInterface
    {
        return new ShippingMethodTranslation();
    }
}
