<?php

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MenuBuilder
{
    private string $username;

    public function __construct(private FactoryInterface $factory, TokenStorageInterface $tokenStorage, private TranslatorInterface $translator)
    {
        if ($tokenStorage->getToken() && $tokenStorage->getToken()->getUser()) {
            $this->username = $tokenStorage->getToken()->getUser()->getUsername();
        }
    }

    public function createUserMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'list-unstyled');

        $this->addProfileMenu($menu);
        $menu->addChild('hr', ['label' => '<hr>', 'labelAttributes' => ['class' => 'normal'], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.logout'), ['label' => '<span class="icon-off"></span>' . $this->translator->trans('menu.logout'), 'route' => 'logout', 'extras' => ['safe_label' => true]]);

        return $menu;
    }

    public function createProfileMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav nav-tabs nav-stacked');

        $this->addProfileMenu($menu);

        return $menu;
    }

    private function addProfileMenu(ItemInterface $menu): void
    {
        $menu->addChild($this->translator->trans('menu.profile'), ['label' => '<span class="icon-vcard"></span>' . $this->translator->trans('menu.profile'), 'route' => 'my_profile', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.settings'), ['label' => '<span class="icon-tools"></span>' . $this->translator->trans('menu.settings'), 'route' => 'edit_profile', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.change_password'), ['label' => '<span class="icon-key"></span>' . $this->translator->trans('menu.change_password'), 'route' => 'change_password', 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.configure_2fa'), ['label' => '<span class="icon-mobile"></span>' . $this->translator->trans('menu.configure_2fa'), 'route' => 'user_2fa_configure', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_packages'), ['label' => '<span class="icon-box"></span>' . $this->translator->trans('menu.my_packages'), 'route' => 'user_packages', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
        $menu->addChild($this->translator->trans('menu.my_favorites'), ['label' => '<span class="icon-leaf"></span>' . $this->translator->trans('menu.my_favorites'), 'route' => 'user_favorites', 'routeParameters' => ['name' => $this->username], 'extras' => ['safe_label' => true]]);
    }
}
