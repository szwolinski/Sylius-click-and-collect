<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Entity;

use App\ClickAndCollect\Form\Type\PickupPointType;
use App\ClickAndCollect\Grid\PickupPointGrid;
use Sylius\Component\Core\Model\ShippingMethodInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Update;
use Sylius\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'app_pickup_point')]
#[AsResource(
    templatesDir: '@SyliusAdmin/shared/crud'
)]
#[Index(routePrefix: '/admin', grid: PickupPointGrid::class)]
#[Create(routePrefix: '/admin', formType: PickupPointType::class)]
#[Update(routePrefix: '/admin', formType: PickupPointType::class)]
#[Delete(routePrefix: '/admin')]
#[BulkDelete(routePrefix: '/admin')]
class PickupPoint implements ResourceInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: 'string')]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $address = null;

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = true;

    #[ORM\ManyToMany(targetEntity: ShippingMethodInterface::class)]
    #[ORM\JoinTable(name: 'app_pickup_point_shipping_method')]
    private Collection $shippingMethods;

    public function __construct()
    {
        $this->shippingMethods = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function getShippingMethods(): Collection
    {
        return $this->shippingMethods;
    }

    public function hasShippingMethod(ShippingMethodInterface $shippingMethod): bool
    {
        return $this->shippingMethods->contains($shippingMethod);
    }

    public function addShippingMethod(ShippingMethodInterface $shippingMethod): void
    {
        if (!$this->hasShippingMethod($shippingMethod)) {
            $this->shippingMethods->add($shippingMethod);
        }
    }

    public function removeShippingMethod(ShippingMethodInterface $shippingMethod): void
    {
        if ($this->hasShippingMethod($shippingMethod)) {
            $this->shippingMethods->removeElement($shippingMethod);
        }
    }
}
