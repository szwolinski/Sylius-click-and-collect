<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Shipping;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ClickAndCollectConfigurationType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entry_type' => ClickAndCollectChannelConfigurationType::class,
            'entry_options' => static fn (ChannelInterface $channel): array => [
                'channel' => $channel,
                'label' => $channel->getName(),
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChannelCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'shipping_calculator_click_and_collect';
    }
}
