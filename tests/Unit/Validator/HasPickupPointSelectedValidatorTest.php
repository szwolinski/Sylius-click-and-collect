<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\ClickAndCollect\Entity\PickupPoint;
use App\ClickAndCollect\Validator\Constraints\HasPickupPointSelected;
use App\ClickAndCollect\Validator\Constraints\HasPickupPointSelectedValidator;
use App\Entity\Order\Order;
use App\Entity\Shipping\Shipment;
use App\Entity\Shipping\ShippingMethod;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class HasPickupPointSelectedValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): HasPickupPointSelectedValidator
    {
        return new HasPickupPointSelectedValidator();
    }

    public function testRequiresPickupPointForEligibleMethod(): void
    {
        $order = new Order();
        $shipment = new Shipment();

        $method = new ShippingMethod();
        $method->setCode('click_and_collect');
        $method->addPickupPoint(new PickupPoint());

        $shipment->setMethod($method);
        $order->addShipment($shipment);

        $this->validator->validate($order, new HasPickupPointSelected());

        self::buildViolation('app.ui.pickup_point_required')
            ->assertRaised();
    }

    public function testItIgnoresMethodsWithoutPickupPoints(): void
    {
        $order = new Order();
        $shipment = new Shipment();

        $method = new ShippingMethod();
        $method->setCode('standard_courier');

        $shipment->setMethod($method);
        $order->addShipment($shipment);

        $this->validator->validate($order, new HasPickupPointSelected());

        self::assertNoViolation();
    }
}
