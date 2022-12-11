<?php

namespace App\WebContent;

use App\Entity\Article;
use App\Entity\Service;
use App\Entity\Category;
use App\Entity\CmsComponent;
use App\Entity\Organization;
use App\Entity\LocalBusiness;
use App\Entity\Product\Product;
use App\Entity\SpecialAnnouncement;

class Component extends AbstractWebContent
{
    public function getData($webpage)
    {
        $components = json_decode($webpage->getComponent('fr_FR'), true);
        $componentSorted = [];

        if ($components) {
            for ($i=0; $i < count($components); $i++) {
                $key = explode('.', $components[$i]['code']);
                $key = $key[1];
                if (!array_key_exists($key, $componentSorted)) {
                    $componentSorted[$key] = [];
                }
                //if (array_key_exists('devkey', $components[$i]['data'])) {
                //    $componentSorted[$key][$components[$i]['data']['devkey']] = $components[$i]['data'];
                //} else {
                //    array_push($componentSorted[$key], $components[$i]['data']);
                //}
                array_push($componentSorted[$key], $components[$i]['data']);
            }
        }
        
        return $componentSorted;
    }

    public function getComponents($webpage)
    {
       
        $components = json_decode($webpage->getComponents('fr_FR'), true);
        $componentSorted = [];

        if ($components) {
            
            for ($i=0; $i < count($components); $i++) {
                if(isset($components[$i]['data']['searchAction'])) {
                    $components[$i]['data']['results'] = $this->getDataBySearchAction(
                        $components[$i]['data']['searchAction']
                    );
                }
                array_push($componentSorted, $components[$i]['data']);
            }
        }
        
        return $componentSorted;
    }
   
    // Return an array of Templates for one components
    public function getTemplates($code)
    {
        $component = $this->manager->getRepository(CmsComponent::class)->findOneBy(['code' => $code]);

        if ($component == null) {
            return;
        }

        $templates = [];

        foreach ($component->getTemplates() as $template) {
            $templates += [
                $template->getName() => $template->getCode()
            ];
        }

        return $templates;
    }

    // Return an array of Templates for one components
    public function getStyles($code)
    {
        $component = $this->manager->getRepository(CmsComponent::class)->findOneBy(['code' => $code]);

        if ($component == null) {
            return;
        }

        $styles = [];
        $styles += [
            'Défaut' => ''
        ];

        foreach ($component->getStyles() as $style) {
            $styles += [
                $style->getName() => $style->getCode()
            ];
        }

        return $styles;
    }

    public function getComponentViews()
    {
        $views = [];
        $path = $this->container->getParameter('website_directory') . DIRECTORY_SEPARATOR . 'components';
        if($this->filesystem->exists($path)) {
            $this->finder->depth('== 0');
            $this->finder->files()->in($path);
            if ($this->finder->hasResults()) {
                foreach ($this->finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $filePath = $file->getPath();
                    $fileNameWithExtension = $file->getRelativePathname();
                    $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                    $filename = pathinfo($fileNameWithExtension,  PATHINFO_FILENAME);
                    $views[$fileNameWithExtension] = $filename;
                }
            }
    
        }
        ksort($views);
        
        return $views;
    }


    public function getIcons($code)
    {
        $icons = [];
        // $path = $this->container->getParameter('path_directory_icon');
        // if($this->filesystem->exists($path)) {
        //     $this->finder->depth('== 0');
        //     $this->finder->files()->in($path);
        //     if ($this->finder->hasResults()) {
        //         foreach ($this->finder as $file) {
        //             $absoluteFilePath = $file->getRealPath();
        //             $filePath = $file->getPath();
        //             $fileNameWithExtension = $file->getRelativePathname();
        //             $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
        //             $filename = pathinfo($fileNameWithExtension,  PATHINFO_FILENAME);
        //             $icons[$fileNameWithExtension] = $filename;
        //         }
        //     }
    
        // }
        
        return $icons;
    }

    private function getDataBySearchAction($searchAction)
    {
        /**
         * @TODO : À REFACTO DANS UN SERVICE
         */
        $results = [];
        switch ($searchAction['mainEntityOfPage']) {
            case 'default_article':
              
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                $results = $this->manager->getRepository(Article::class)
                        ->findBy($criteria, $orderBy, $limit, $offset);
                     
                break;

            case 'default_local_business':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    // $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
             
                $results = $this->manager->getRepository(LocalBusiness::class)
                        ->findBy($criteria, $orderBy, $limit, $offset);
             
                break;
            
            case 'default_special_announcement':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                $results = $this->manager->getRepository(SpecialAnnouncement::class)
                        ->findBy($criteria, $orderBy, $limit, $offset);
                
                break;
            case 'default_manufacturer':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                
                $results = $this->manager->getRepository(Organization::class)
                        ->findBy($criteria, $orderBy, $limit, $offset);
                
                break;
            case 'default_special_announcement':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    // $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                
                $results = $this->manager->getRepository(SpecialAnnouncement::class)
                    ->findBy($criteria, $orderBy, $limit, $offset);
                
                break;
            case 'default_service':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    // $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                
                $results = $this->manager->getRepository(Service::class)
                    ->findBy($criteria, $orderBy, $limit, $offset);
                
                break;
            case 'default_product':
                $criteria = [];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    // $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                
                $results = $this->manager->getRepository(Product::class)
                    ->findBy($criteria, $orderBy, $limit, $offset);
                
                break;
            case 'type_product':
                
                $criteria = [ 'type' => 'produit' ];
                if(count($searchAction['tags']) > 0) {
                    // quand les recherches évolueront nous feront aussi évoluer ce code
                    // $criteria = ['category' => $searchAction['tags'][0]];
                }

                $orderBy = [];
                if(isset($searchAction['orderByDate'])) {
                    $orderBy['createdAt'] = $searchAction['orderByDate'];
                }
                $limit = (isset($searchAction['limitResult']))? $searchAction['limitResult']: null;
                $offset = (isset($searchAction['offsetResult']))? $searchAction['offsetResult']: null;
                
                $results = $this->manager->getRepository(Category::class)
                        ->findBy($criteria, $orderBy, $limit, $offset);
// dump($results);die;
                // $results = $this->manager->getRepository(Product::class)
                //         ->findBy($criteria, $orderBy, $limit, $offset);
                
                // $results = array_reduce($results, function (
                //     array $accumulator, $element
                // ) {
                //     $accumulator[$element->getMainTaxon()->getSlug()]['type'] = $element->getMainTaxon();
                //     $accumulator[$element->getMainTaxon()->getSlug()]['products'][] = $element;
                    
                //     return $accumulator;
                // }, []);

                break;
            default:
                
                break;
        }

        return $results;
    }

    public function updateSlug($entity)
    {
        $slug = $this->slugger->slug($entity->getTranslatable()->getName() . ' ' . $entity->getLocale())->lower()->toString();
        
        $entity->setSlug($slug);

        return $entity;
    }
}
