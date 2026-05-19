<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Validator\Constraints;

use App\Entity\Shipping\Shipment;
use App\Entity\Shipping\ShippingMethod;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class HasPickupPointSelectedValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof HasPickupPointSelected) {
            throw new UnexpectedTypeException($constraint, HasPickupPointSelected::class);
        }

        if (!$value instanceof OrderInterface) {
            return;
        }

        /** @var Shipment $shipment */
        foreach ($value->getShipments() as $index => $shipment) {
            $method = $shipment->getMethod();

            if ($method === null) {
                continue;
            }

            if (!$method instanceof ShippingMethod) {
                continue;
            }

            if (!$method->hasPickupPoints()) {
                continue;
            }

            if ($shipment->getPickupPoint() === null) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        }
    }
}
