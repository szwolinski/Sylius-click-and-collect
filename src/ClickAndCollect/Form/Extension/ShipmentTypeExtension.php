<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Form\Extension;

use App\ClickAndCollect\Entity\PickupPoint;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\ShipmentType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

final class ShipmentTypeExtension extends AbstractTypeExtension
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pickupPoint', EntityType::class, [
            'class' => PickupPoint::class,
            'choice_label' => 'name',
            'required' => false,
            'mapped' => true,
        ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [ShipmentType::class];
    }
}
