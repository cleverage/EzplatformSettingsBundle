<?php

namespace Ezplatform\SettingsBundle\PlatformAdminUI\EventListener;

use eZ\Publish\Core\MVC\Symfony\Security\Authorization\Attribute;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MainMenuBuilder;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class MainMenuBuilderListener implements EventSubscriberInterface
{

    /**
     * @var \Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [ConfigureMenuEvent::MAIN_MENU => 'onMainMenuBuild'];
    }

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     */
    public function onMainMenuBuild(ConfigureMenuEvent $event)
    {
        if (!$this->authorizationChecker->isGranted(new Attribute('settings', 'manage'))) {
            return;
        }

        $this->addSettingsSubMenu($event->getMenu());
    }

    /**
     * Adds the Netgen Tags submenu to eZ Platform admin interface.
     *
     * @param \Knp\Menu\ItemInterface $menu
     */
    private function addSettingsSubMenu(ItemInterface $menu)
    {
        $menuOrder = $this->getNewMenuOrder($menu);

        $menu
            ->addChild('cleverage_settings', ['route' => 'cleverage_settings'])
            ->setLabel('Settings');

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

        $menuOrder[] = 'cleverage_settings';

        return $menuOrder;
    }
}
