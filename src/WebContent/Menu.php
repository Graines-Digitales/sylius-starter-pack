<?php

namespace App\WebContent;

use App\Entity\WebPage;
use App\Entity\Organization;
use App\Entity\LocalBusiness;


class Menu extends AbstractWebContent
{
   public function mainItems($router) 
   {
      
      $routeCollection = $router->getRouteCollection();
      $configurationProject = $this->container->getParameter('configuration_project');
      
      $menu = $configurationProject['menu']['main'];
      $slugs = $configurationProject['web_pages'];

      $webPages = $this->manager->getRepository(WebPage::class)->finAllArrayResult();
      foreach($menu as $key=>$value) {
         $route = $routeCollection->get($value['route']);
         $slug = $slugs[$key]['slug'];
         $webPage = $webPages[$slug];
         $menu[$key]['label'] = (null !== $webPage->getAlternativeHeadline())? $webPage->getAlternativeHeadline(): $webPage->getHeadline();
         $menu[$key]['slug'] = $webPage->getSlug();
         $menu[$key]['params'] = [];
         if(isset($slugs[$key]) && !empty($route->getRequirements())){
            $menu[$key]['params'] = [ 'slug' => $webPage->getSlug() ];
         }
      }

      return $menu;
   }

   public function footerItems($router) 
   {

      $routeCollection = $router->getRouteCollection();
      $configurationProject = $this->container->getParameter('configuration_project');
      
      $menu = $configurationProject['menu']['footer'];
      $slugs = $configurationProject['web_pages'];

      $webPages = $this->manager->getRepository(WebPage::class)->finAllArrayResult();
      foreach($menu as $key=>$value) {
         $route = $routeCollection->get($value['route']);
         $slug = $slugs[$key]['slug'];
         $webPage = $webPages[$slug];
         $menu[$key]['label'] = $webPage->getHeadline();
         $menu[$key]['slug'] = $webPage->getSlug();
         $menu[$key]['params'] = [];
         if(isset($slugs[$key]) && !empty($route->getRequirements())){
            $menu[$key]['params'] = [ 'slug' => $webPage->getSlug() ];
         }
      }

      return $menu;
   }

   public function breadcrumb()
   {
      return [ 'my breadcrumb' ];
   }

   public function getLegalNoticeSlug()
   {
      return $this->configurationService->getLegalNoticeSlug();
   }
}
