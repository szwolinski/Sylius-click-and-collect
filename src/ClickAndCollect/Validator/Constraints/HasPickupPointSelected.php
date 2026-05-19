<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
final class HasPickupPointSelected extends Constraint
{
    public string $message = 'app.ui.pickup_point_required';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
