<?php

declare(strict_types=1);

namespace App\Tests\Unit\ClickAndCollect\Shipping;

use App\ClickAndCollect\Shipping\ClickAndCollectCalculator;
use App\Tests\Unit\ClickAndCollect\Shipping\Factory\ShipmentTestFactory;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;

final class ClickAndCollectCalculatorTest extends TestCase
{
    private ClickAndCollectCalculator $calculator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calculator = new ClickAndCollectCalculator();
    }

    public function testItFailsWhenChannelIsMissingInConfiguration(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 1000);

        self::expectException(MissingChannelConfigurationException::class);

        $this->calculator->calculate($shipment, []);
    }

    public function testItReturnsZeroFeeWhenHandlingFeeIsZero(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 5000);

        $configuration = [
            'US' => [
                'handling_fee' => 0,
                'free_above' => 10000,
            ],
        ];

        $this->assertSame(0, $this->calculator->calculate($shipment, $configuration));
    }

    public function testItReturnsHandlingFeeIfOrderTotalIsBelowFreeAboveThreshold(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 5000);

        $configuration = [
            'US' => [
                'handling_fee' => 500,
                'free_above' => 10000,
            ],
        ];

        $this->assertSame(500, $this->calculator->calculate($shipment, $configuration));
    }

    public function testItReturnsZeroIfOrderTotalIsAboveFreeAboveThreshold(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 15000);

        $configuration = [
            'US' => [
                'handling_fee' => 500,
                'free_above' => 10000,
            ],
        ];

        $this->assertSame(0, $this->calculator->calculate($shipment, $configuration));
    }

    public function testItReturnsZeroIfOrderTotalIsSameAsFreeAboveThreshold(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 10000);

        $configuration = [
            'US' => [
                'handling_fee' => 500,
                'free_above' => 10000,
            ],
        ];

        $this->assertSame(0, $this->calculator->calculate($shipment, $configuration));
    }

    public function testItReturnsHandlingFeeIfFreeAboveThresholdIsNull(): void
    {
        $shipment = ShipmentTestFactory::createWithChannelAndTotal('US', 150000);

        $configuration = [
            'US' => [
                'handling_fee' => 100,
                'free_above' => null,
            ],
        ];

        $this->assertSame(100, $this->calculator->calculate($shipment, $configuration));
    }
}
