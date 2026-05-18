<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Shipping;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Component\Core\Model\ChannelInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class ClickAndCollectChannelConfigurationType extends AbstractType
{
    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ChannelInterface $channel */
        $channel = $options['channel'];
        $baseCurrency = $channel->getBaseCurrency();

        Assert::notNull($baseCurrency, sprintf('Channel with code "%s" must have a base currency assigned.', $channel->getCode()));
        $currencyCode = $baseCurrency->getCode();

        $builder
            ->add('handling_fee', MoneyType::class, [
                'label' => 'sylius.form.shipping_calculator.click_and_collect.handling_fee',
                'currency' => $currencyCode,
                'required' => true,
            ])
            ->add('free_above', MoneyType::class, [
                'label' => 'sylius.form.shipping_calculator.click_and_collect.free_above',
                'currency' => $currencyCode,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('channel');
        $resolver->setAllowedTypes('channel', ChannelInterface::class);
    }
}
