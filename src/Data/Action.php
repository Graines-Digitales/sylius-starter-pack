<?php

namespace App\Data;

use App\Entity\Category;
use App\Entity\IconMediaObject;
use App\Entity\ImageMediaObject;
use Mni\FrontYAML\Parser;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use App\Tools\Media;


class Action
{
    private $container;

    private $entityManager;

    private $webPageAction;

    private $articleAction;

    private $componentAction;

    private $tripAction;

    private $organizationAction;

    private $mediaService;

    public function __construct(
        ContainerInterface $container
        , EntityManagerInterface $entityManager
        , WebPageAction $webPageAction
        , ArticleAction $articleAction
        , CategoryAction $categoryAction
        , ComponentAction $componentAction
        , TripAction $tripAction
        , OrganizationAction $organizationAction
        , Media $mediaService
    ){
        $this->container = $container;
        $this->entityManager = $entityManager;
        $this->webPageAction = $webPageAction;
        $this->articleAction = $articleAction;
        $this->categoryAction = $categoryAction;
        $this->componentAction = $componentAction;
        $this->tripAction = $tripAction;
        $this->organizationAction = $organizationAction;
        $this->mediaService = $mediaService;
    }

    public function importImages()
    {
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $configurationProject = $this->container->getParameter('configuration_project');
        $imageMimeTypes = $configurationProject['media_encoding_formats']['image'];

        $imagesPath = $kernelProjectDir . '/content/images';
        $svgsPath = $kernelProjectDir . '/content/svgs';

        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->files()->in($imagesPath);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $dirname = pathinfo(pathinfo($file->getRealPath(), PATHINFO_DIRNAME), PATHINFO_BASENAME);
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $category = null;
                if('images' !== $dirname) {
                    $data['name'] = $dirname;
                    $category = $this->entityManager->getRepository(Category::class)
                        ->findOneBySlug($data['name']);
                    if(null === $category) {
                        
                        $category = $this->categoryAction->create($data);
                    }
                }
                $file = new File($absoluteFilePath);
                if (in_array($file->getMimeType(), $imageMimeTypes)) {
                    $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/image/' . $filename);
                    $entity = $this->entityManager->getRepository(ImageMediaObject::class)
                    ->findOneBy([ 'filename' => $filename ]);
                    if(null === $entity) {
                        
                        $entity = $this->mediaService->defineEntityMediaFromFile2($file, $category);
                    }
                    $this->entityManager->persist($entity);
                }
            }
            $this->entityManager->flush();
            
        }

        $filesystem = new Filesystem();
        $finder = new Finder();
        $finder->files()->in($svgsPath);
        if ($finder->hasResults()) {
            foreach ($finder as $file) {
                $absoluteFilePath = $file->getRealPath();
                $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                $file = new File($absoluteFilePath);
                $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/icon/' . $filename);
                $entity = $this->entityManager->getRepository(IconMediaObject::class)
                ->findOneBy([ 'filename' => $filename ]);
                if(null === $entity) {
                    
                    $entity = $this->mediaService->defineIconMediaFromFile($file);
                }
                $this->entityManager->persist($entity);
            }
            $this->entityManager->flush();
        }
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
                            $entity = $this->dataServicesDispatch($data, $folder);
                            $this->entityManager->persist($entity);
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
        $organization = $this->organizationAction->create($data);
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
               
                $category = $this->entityManager->getRepository(Category::class)->findOneBySlug($data['slug']);
                if(null !== $category) {
                    
                    return $category;
                }

                return $this->categoryAction->create($data);
              
                break;
            case 'web_pages':
                if(isset($data['content'])) {
                    $data['text'] = $data['content'];
                    unset($data['content']);
                }
                
                return $this->webPageAction->create($data);
                
                break;
            case 'articles':
                if(isset($data['content'])) {
                    $data['articleBody'] = $data['content'];
                    unset($data['content']);
                }

                return $this->articleAction->create($data);
                
                break;
            case 'components':
              
                return $this->componentAction->create($data);
                
                break;
            case 'social_links':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->organizationAction->create($data);
                
                break;
            case 'travels':
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->tripAction->create($data);
                
                break;
            default:
                # code...
                break;
        }
    }
}
