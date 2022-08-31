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
        $locales = $this->container->get('sylius.repository.locale')->findAll();
        foreach($locales as $locale) {
            $localeCode = $locale->getCode();
            $locale = current(explode('_', $localeCode));
            // dump($locale);die;
            if('fr' === $locale) {
                /**
                 * MEDIA OBJECTS
                 */

                /**
                 * CATEGORIES
                 */
                $parser = new Parser();
                $finder = new Finder();
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'categories';
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
            
                /**
                 * WEBPAGES
                 */
                $parser = new Parser();
                $finder = new Finder();
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'web_pages';
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
                
                /**
                 * ARTICLES
                */
                $parser = new Parser();
                $finder = new Finder();
                $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . 'articles';
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

                /**
                 * SOCIAL LINKS
                 */
                $parser = new Parser();
                $finder = new Finder();
                $path = $contentPath . DIRECTORY_SEPARATOR  . 'organizations';
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

                 /**
                 * COMPONENTS
                 */
                $parser = new Parser();
                $finder = new Finder();
                $path = $contentPath . DIRECTORY_SEPARATOR  . $locale . DIRECTORY_SEPARATOR . 'components';
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
                            $component = $this->dataService->createComponentDemand($data, $localeCode);
                            $this->entityManager->persist($component);
                        } else if('json' === $extension) {
                            $data = json_decode(
                                file_get_contents($absoluteFilePath)
                                , true
                            );
                            // $component = $this->dataService->createComponentDemand($data, $localeCode);
                            // $this->entityManager->persist($component);
                        }
                        
                    }
                    $this->entityManager->flush();
                }
            }
        }

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
}
