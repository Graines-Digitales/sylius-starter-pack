<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\TwigFunction;
use App\WebContent\Menu;
use App\Entity\Organization;
use App\Entity\LocalBusiness;
use Twig\Extension\AbstractExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class AppExtension extends AbstractExtension
{
    private $container;

    private $manager;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $manager
    ){
        $this->container = $container;
        $this->manager = $manager;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('basename', [$this, 'basenameFilter']),
            new TwigFilter('youtube', [$this, 'youtubeFilter']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('twoParagraphs', [$this, 'getTwoParagraphs']),
            new TwigFunction('configuration', [$this, 'getConfigurationProject']),
            new TwigFunction('allLocalBusinesses', [$this, 'getAllLocalBusinesses']),
            new TwigFunction('localBusinessesFromServices', [$this, 'getLocalBusinessesFromServices']),
            new TwigFunction('localBusinessesFromProductCategories', [$this, 'getLocalBusinessesFromProductCategories']),
            new TwigFunction('localBusinessFromProduct', [$this, 'getLocalBusinessFromProduct']),
            new TwigFunction('mainCategoriesFromProducts', [$this, 'getMainCategoriesFromProducts']),
            new TwigFunction('categoriesFromProducts', [$this, 'getCategoriesFromProducts']),
            new TwigFunction('featuresFromProduct', [$this, 'getFeaturesFromProduct']),
        ];
    }

    /**
     * @var string $value
     * @return string
     */
    public function youtubeFilter($url)
    {
        $id = null;
        if(preg_match(
            '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i'
            , $url
            , $match)
        ) {
            $id = $match[1];
        }


       return $id;
    }

    /**
     * @var string $value
     * @return string
     */
    public function basenameFilter($value, $suffix = '')
    {
       return basename($value, $suffix);
    }

    function getTwoParagraphs($string, $length) 
    {
        if( strlen( $string ) <= $length ) return $string;
    
        $start = round( $length / 2 );
        $start = strlen( substr( $string, 0, ( strpos( substr( $string, $start ), ' ' ) + $start ) ) );
        $start = substr( $string, 0, $start );

        $end = strlen( substr( $string, 0, strrpos( substr( $string, strlen($start) - 1 ), ' ' ) ) );
        $end = trim((substr( $string, strlen($start), $end)));   
  
        return  [ $start, $end ];
    }

    function getConfigurationProject() 
    {
       return $this->container->getParameter('configuration_project');
    }

    function getCategoriesFromProducts($data) 
    {
        return array_reduce($data, function (
            array $accumulator, $element
        ) {
            foreach ($element->getProductTaxons() as $productTaxon) {
                $slug = $productTaxon->getTaxon()->getParent()->getSlug();
                if (!in_array($slug, [ 'root', 'point-de-vente' ])) {
                    $accumulator[$productTaxon->getTaxon()->getSlug()] = $productTaxon->getTaxon();
                }
            }
            
            return $accumulator;
        }, []);
    }

    function getMainCategoriesFromProducts($data) 
    {
        return array_reduce($data, function (
            array $accumulator, $element
        ) {
            $accumulator[$element->getMainTaxon()->getSlug()] = $element->getMainTaxon();
            
            return $accumulator;
        }, []);
    }

    function getLocalBusinessesFromServices($data) 
    {
        return array_reduce($data, function (
            array $accumulator, $element
        ) {
            foreach($element->getLocalBusinesses() as $localBusiness) { 
                $accumulator[$localBusiness->getSlug()] = $localBusiness;
            }
            
            return $accumulator;
        }, []);
    }

    function getLocalBusinessesFromProductCategories($data) 
    {
        return array_reduce($data, function (
            array $accumulator, $element
        ) {
            foreach($element->getLocalBusinesses() as $localBusiness) { 
                $accumulator[$localBusiness->getSlug()] = $localBusiness;
            }
            
            return $accumulator;
        }, []);
    }

    function getFeaturesFromProduct($data) 
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $excludes = ['root', 'local-business'];
        $array = [];
        foreach($data->getTaxons() as $taxon) {
            if(!in_array($taxon->getParent()->getSlug(), $excludes)){
                array_push($array, $taxon);
            }
        }
       
        return $array;
    }

    function getLocalBusinessFromProduct($data) 
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $slug = $configurationProject['product']['local_business']['slug'];
        foreach($data->getTaxons() as $taxon) {
            
            if($slug === $taxon->getParent()->getSlug()) {
                $organization = $this->manager->getRepository(Organization::class)
                    ->findOneBySlug($taxon->getSlug());
                 
                return $this->manager->getRepository(LocalBusiness::class)
                    ->findOneBy(['organization' => $organization]);

            }
        }
       
        return null;
    }

    function getAllLocalBusinesses() 
    {
        return $organization = $this->manager->getRepository(LocalBusiness::class)
                    ->findAll();
                     
    }
}