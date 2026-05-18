<?php

declare(strict_types=1);

namespace App\ClickAndCollect\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: 'sylius.menu.admin.main')]
final class AdminMenuListener
{
    public function __invoke(MenuBuilderEvent $event): void
    {
        $event->getMenu()
            ->getChild('sales')
            ?->addChild('pickup_points', [
                'route' => 'app_pickup_point_index',
            ])
            ->setLabel('app.ui.pickup_points')
        ;
    }
}
