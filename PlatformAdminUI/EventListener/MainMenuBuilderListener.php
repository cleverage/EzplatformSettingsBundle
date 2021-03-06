<?php

namespace Masev\SettingsBundle\PlatformAdminUI\EventListener;

use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class MainMenuBuilderListener implements EventSubscriberInterface
{

    public static function getSubscribedEvents()
    {
        return [ConfigureMenuEvent::MAIN_MENU => 'onMainMenuBuild'];
    }

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     */
    public function onMainMenuBuild(ConfigureMenuEvent $event)
    {
        $this->addMasevSubMenu($event->getMenu());
    }

    /**
     * Adds the Netgen Tags submenu to eZ Platform admin interface.
     *
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function addMasevSubMenu(ItemInterface $menu)
    {
        $menuOrder = $this->getNewMenuOrder($menu);

        $menu
            ->addChild('masev', ['route' => 'masev_settings'])
            ->setLabel('Configuration');

        $menu->reorderChildren($menuOrder);
    }

    /**
     * Returns the new menu order.
     *
     * @param \Knp\Menu\ItemInterface $menu
     *
     * @return array
     */
    private function getNewMenuOrder(ItemInterface $menu)
    {
        $menuOrder = array_keys($menu->getChildren());

        $menuOrder[] = 'masev';

        return $menuOrder;
    }
}
