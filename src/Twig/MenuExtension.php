<?php

namespace App\Twig;

use App\WebContent\Menu;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;


class MenuExtension extends AbstractExtension
{
    private $container;

    private $menu;

    private $router;

    public function __construct(
        ContainerInterface $container,
        Menu $menu,
        RouterInterface $router
    ){
        $this->container = $container;
        $this->menu = $menu;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('mainMenuHeader', [$this, 'getMainMenuHeader']),
            new TwigFunction('brandsMenuFooter', [$this, 'getBrandsMenuFooter']),
            new TwigFunction('localBusinessesMenuFooter', [$this, 'getLocalBusinessesMenuFooter']),
            new TwigFunction('mainMenuFooter', [$this, 'getMainMenuFooter']),
            new TwigFunction('manufacturerTagsFooter', [$this, 'getManufacturerTagsFooter']),
            new TwigFunction('legalNoticeSlug', [$this, 'getLegalNoticeSlug']),
        ];
    }

    public function getLegalNoticeSlug()
    {
        return $this->menu->getLegalNoticeSlug();
    }

    public function getMainMenuHeader()
    {
        return $this->menu->mainItems($this->router);
    }

    public function getMainMenuFooter()
    {
        return $this->menu->footerItems($this->router);
    }

    public function getLocalBusinessesMenuFooter()
    {
        
        return $this->menu->localBusinessesFooterItems();
    }

    public function getBrandsMenuFooter()
    {
        return $this->menu->brandsFooterItems();
    }

    public function getManufacturerTagsFooter()
    {
        return $this->menu->manufacturerTagsFooterItems();
    }
}