<?php

declare(strict_types=1);

namespace App\Tests\Unit\ClickAndCollect\Shipping\Factory;

use Sylius\Component\Core\Model\Channel;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\Shipment;
use Sylius\Component\Core\Model\ShipmentInterface;

final class ShipmentTestFactory
{
    public static function createWithChannelAndTotal(string $channelCode, int $itemsTotal): ShipmentInterface
    {
        $channel = new Channel();
        $channel->setCode($channelCode);

        $order = new class($channel, $itemsTotal) extends Order {
            public function __construct(
                private readonly ChannelInterface $testChannel,
                private readonly int $testItemsTotal,
            ) {
                parent::__construct();
            }

            public function getChannel(): ChannelInterface
            {
                return $this->testChannel;
            }

            public function getItemsTotal(): int
            {
                return $this->testItemsTotal;
            }
        };

        $shipment = new Shipment();
        $shipment->setOrder($order);

        return $shipment;
    }
}
