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

    public function run($contentPath, $folders)
    {
        
        $locales = $this->container->get('sylius.repository.locale')->findAll();
        foreach($locales as $locale) {
            $localeCode = $locale->getCode();
            $locale = current(explode('_', $localeCode));
            if('fr' === $locale) {
                foreach($folders as $folder) {
                    $finder = new Finder();
                    $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $folder;
                    $finder->depth('== 0');
                    $finder->files()->in($path);
                    if ($finder->hasResults()) {
                        foreach ($finder as $file) {
                            $absoluteFilePath = $file->getRealPath();
                            $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                            $data = $this->extractData($absoluteFilePath, $extension);
                            $category = $this->dataServicesDispatch($data, $folder);
                            $this->entityManager->persist($category);
                        }
                        $this->entityManager->flush();
                    }
                }
            }
        }
    }

    public function getMainOrganization($contentPath)
    {
        $parser = new Parser();
        $filesystem = new Filesystem();
        $filepath = $contentPath . DIRECTORY_SEPARATOR . 'main_organization.md';
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

    public function extractData($absoluteFilePath, $extension)
    {
        $data = [];
        
        switch ($extension) {
            case 'md':
                $parser = new Parser();
                $result = $parser->parse(file_get_contents($absoluteFilePath), false);
                $data = $result->getYaml();
                $data['content'] = $result->getContent();
                
                break;
            case 'json':
                $data = json_decode(
                    file_get_contents($absoluteFilePath)
                    , true
                );
                break;
        }

        return $data;
    }

    public function dataServicesDispatch($data, $folder)
    {
        switch ($folder) {
            case 'categories':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->dataService->createCategoryDemand($data);
              
                break;
            case 'web_pages':
                if(isset($data['content'])) {
                    $data['text'] = $data['content'];
                    unset($data['content']);
                }
                
                return $this->dataService->createWebPageDemand($data);
                
                break;
            case 'articles':
                if(isset($data['content'])) {
                    $data['articleBody'] = $data['content'];
                    unset($data['content']);
                }

                return $this->dataService->createArticleDemand($data);
                
                break;
            case 'components':
              
                return $this->dataService->createComponentDemand($data);
                
                break;
            case 'social_links':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->dataService->createOrganizationDemand($data);
                
                break;
            case 'travels':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->dataService->createTripDemand($data);
                
                break;
            default:
                # code...
                break;
        }
    }
}
