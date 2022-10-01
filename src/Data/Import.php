<?php

namespace App\Data;

use Mni\FrontYAML\Parser;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Service à supprimer quand sera acquis la notion d'architecture hexagonale
 */
class Import
{
    private $container;

    private $entityManager;

    private $dataService;

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $entityManager
        , Action $dataService
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->dataService = $dataService;
    }

    public function run($contentPath)
    {
        $response = [];
        $filesystem = new Filesystem();
        $locales = $this->container->get('sylius.repository.locale')->findAll();
        foreach($locales as $locale) {
            $localeCode = $locale->getCode();
            $locale = current(explode('_', $localeCode));
            if('fr' === $locale) {

                $this->createMediasAndCategoriesFromAllData($contentPath, $locale);

                /**
                 * CATEGORIES
                 */
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'categories';
                if($filesystem->exists($path)) {
                    $this->createCategories($path, $localeCode);
                }
                /**
                 * WEBPAGES
                 */
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'web_pages';
                if($filesystem->exists($path)) {
                    $this->createWebPages($path, $localeCode);
                }
                /**
                 * ARTICLES
                */
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'articles';
                if($filesystem->exists($path)) {
                    $this->createArticles($path, $localeCode);
                }
                /**
                 * SOCIAL LINKS
                 */
                $path = $contentPath . DIRECTORY_SEPARATOR  . 'organizations';
                if($filesystem->exists($path)) {
                    $this->createSocialLinks($path, $localeCode);
                }
                 /**
                 * COMPONENTS
                 */
                $path = $contentPath . DIRECTORY_SEPARATOR  . $locale . DIRECTORY_SEPARATOR . 'components';
                if($filesystem->exists($path)) {
                    $this->createComponents($path, $localeCode);
                }
            }
        }

        return $response;
    }

    public function getMainOrganization($contentPath)
    {
        $parser = new Parser();
        $filesystem = new Filesystem();
        $filepath = $contentPath . DIRECTORY_SEPARATOR . 'organizations/main.md';
        if($filesystem->exists($filepath)) {
            $result = $parser->parse(file_get_contents($filepath), false);

            return $result->getYaml();
        }

        return false;
    }

    public function createMainOrganization($data) 
    {
        $organization = $this->dataService->createOrganizationDemand($data);
        $this->entityManager->persist($organization);
        $this->entityManager->flush();
    }

    private function createMediasAndCategoriesFromAllData($path, $locale)
    {
        dump("createMediasAndCategoriesFromAllData");
        $directoryResourcesPath = $this->container->getParameter('path_directory_resources');
        $imagesUploadFolder = $this->container->getParameter('folder_images_upload');
        dump($directoryResourcesPath);
        dump($imagesUploadFolder);
        $filesystem = new Filesystem();
        $directoryResourcesPath = $directoryResourcesPath . DIRECTORY_SEPARATOR . $imagesUploadFolder;
        if(!$filesystem->exists($directoryResourcesPath)) {
            dump('directoryResourcesPath doesnt exist!');die;
        }
        $parser = new Parser();
        $finder = new Finder();
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                if('md' === $extension && 'main' !== $filename) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    $data['description'] = $result->getContent();
                    dump($data);

                } else if('json' === $extension) {
                    $result = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );

                    dump($absoluteFilePath);
                    $this->gonatoukiExtract($result);

                }
            }
        }

        die;
    }

    private function gonatoukiExtract($result)
    {
        foreach($result as $field=>$data) {

            switch ($field) {
                case 'category':
                    $category = $this->dataService->createCategoryDemand($data);
                    $this->entityManager->persist($category);
                    $this->entityManager->flush();
                    break;
                case 'tags':
                    foreach($data as $k=>$v){
                        $category = $this->dataService->createCategoryDemand($v);
                        $this->entityManager->persist($category);
                        $this->entityManager->flush();
                    }
                    break;
                case ($field == 'primaryImage' || $field == 'secondaryImage'):
                    if(null !== $data) {
                        $imageMediaObject = $this->dataService->createImageMediaObjectDemand($data);
                        
                        $this->entityManager->persist($imageMediaObject);
                        $this->entityManager->flush();
                    }
                    break;
                case ($field === 'gallery' || $field === 'galleryVertical'):
                    if(null !== $data) {
                        
                        foreach($data['imageGalleries'] as $k=>$v){
                            if(null !== $v){
                                $imageMediaObject = $this->dataService->createImageMediaObjectDemand($v['image']);
                                $this->entityManager->persist($imageMediaObject);
                                $this->entityManager->flush();
                            }
                        }
                    }
                    break;
                case 'elements':
                    foreach($data as $k=>$v){
                        $hotelTypicalDayElement = $this->dataService->createHotelTypicalDayElementDemand($v);
                        $this->entityManager->persist($hotelTypicalDayElement);
                        $this->entityManager->flush();
                    }
                    break;
                case 'amenities':
                    foreach($data as $k=>$v){
                        $amenityFeature = $this->dataService->createAmenityFeatureDemand($v);
                        $this->entityManager->persist($amenityFeature);
                        $this->entityManager->flush();
                    }
                    break;
                case 'offers':
                    foreach($data as $k=>$v){
                        $aggregateOffer = $this->dataService->createAggregateOfferDemand($v);
                        $this->entityManager->persist($aggregateOffer);
                        $this->entityManager->flush();
                    }
                    break;
            }
        }
    }

    private function createComponents($path, $locale)
    {
        $parser = new Parser();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                if('md' === $extension && 'main' !== $filename) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    // $data['description'] = $result->getContent();
                    $component = $this->dataService->createComponentDemand($data, $locale);
                    $this->entityManager->persist($component);
                } else if('json' === $extension) {
                    $data = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );
                    // $component = $this->dataService->createComponentDemand($data, $locale);
                    // $this->entityManager->persist($component);
                }
                
            }
            $this->entityManager->flush();
        }
    }

    private function createSocialLinks($path, $locale)
    {
        $parser = new Parser();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                if('md' === $extension && 'main' !== $filename) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    $data['description'] = $result->getContent();
                    $organization = $this->dataService->createOrganizationDemand($data);
                    $this->entityManager->persist($organization);
                } else if('json' === $extension) {
                    $data = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );
                    // $organization = $this->dataService->createOrganizationDemand($data);
                    // $this->entityManager->persist($organization);
                }
            }
            $this->entityManager->flush();
        }
    }

    private function createArticles($path, $locale)
    {
        $parser = new Parser();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                if('md' === $extension) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    $data['articleBody'] = $result->getContent();
                    $article = $this->dataService->createArticleDemand($data);
                $this->entityManager->persist($article);
                } else if('json' === $extension) {
                    $data = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );
                    // $article = $this->dataService->createArticleDemand($data);
                    // $this->entityManager->persist($article);
                } 
                
            }
            $this->entityManager->flush();
        }
    }

    private function createWebPages($path, $locale)
    {
        $parser = new Parser();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                if('md' === $extension) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    $data['text'] = $result->getContent();
                    $webPage = $this->dataService->createWebPageDemand($data);
                    $this->entityManager->persist($webPage);
                } else if('json' === $extension) {
                    $data = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );
                    // $webPage = $this->dataService->createWebPageDemand($data);
                    // $this->entityManager->persist($webPage);
                } 
                
            }
            $this->entityManager->flush();
        }
    }

    private function createCategories($path, $locale)
    {
        $parser = new Parser();
        $finder = new Finder();
        $finder->depth('== 0');
        $finder->files()->in($path);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                // dump($absoluteFilePath);
                if('md' === $extension) {
                    $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                    $data = $result->getYaml();
                    $data['description'] = $result->getContent();
                    $category = $this->dataService->createCategoryDemand($data);
                    $this->entityManager->persist($category);
                } else if('json' === $extension) {
                    $data = json_decode(
                        file_get_contents($absoluteFilePath)
                        , true
                    );
                    $category = $this->dataService->createCategoryDemand($data);
                    $this->entityManager->persist($category);
                } 
            }
            $this->entityManager->flush();
        }
    }
}
