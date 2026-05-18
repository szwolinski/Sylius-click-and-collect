<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Form\Type;

use App\ClickAndCollect\Entity\PickupPoint;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PickupPointType extends AbstractResourceType
{
    public function __construct()
    {
        parent::__construct(PickupPoint::class);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'sylius.ui.code',
            ])
            ->add('name', TextareaType::class, [
                'label' => 'sylius.ui.name',
            ])
            ->add('address', TextareaType::class, [
                'label' => 'sylius.ui.address',
                'required' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'label' => 'sylius.ui.enabled',
                'required' => false,
            ])
            ->add('shippingMethods', ShippingMethodChoiceType::class, [
                'multiple' => true,
                'expanded' => true,
                'label' => 'sylius.ui.shipping_methods',
                'required' => false
            ])
        ;
    }
}
