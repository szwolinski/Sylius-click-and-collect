<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use App\ClickAndCollect\Entity\PickupPoint;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_shipment')]
class Shipment extends BaseShipment
{
    #[ORM\ManyToOne(targetEntity: PickupPoint::class)]
    #[ORM\JoinColumn(name: 'pickup_point_id', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?PickupPoint $pickupPoint = null;

    public function getPickupPoint(): ?PickupPoint
    {
        return $this->pickupPoint;
    }

    public function setPickupPoint(?PickupPoint $pickupPoint): void
    {
        $this->pickupPoint = $pickupPoint;
    }
}
