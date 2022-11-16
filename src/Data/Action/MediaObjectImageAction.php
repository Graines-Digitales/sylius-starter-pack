<?php

namespace App\Data\Action;

use App\Entity\MediaObjectImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

class MediaObjectImageAction
{
    private $slugger;

    private $entityManager;

    private $container;
    
    private $categoryAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , ContainerInterface $container
        , CategoryAction $categoryAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->container = $container;
        $this->categoryAction = $categoryAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(MediaObjectImage::class)
                ->findOneBySlug($data)
            ;
        }

        $filename = (isset($data['filename']))? $data['filename']: null;

        $directoryProject = $this->container->getParameter('kernel.project_dir');
        $folderImage = $this->container->getParameter('directory_image_media_object');
        $filepath = $directoryProject . DIRECTORY_SEPARATOR . 'public' .  $folderImage;
        $filepath.= DIRECTORY_SEPARATOR . $filename;
        $filesystem = new Filesystem();
        
        if(!$filesystem->exists($filepath)) {
            throw new \Exception(sprintf('Error form MediaObjectImageAction filepath image %s', $filepath));
        }

        $entity = $this->entityManager->getRepository(MediaObjectImage::class)
            ->findOneBy(['filename' => $filename ])
        ;
        if(null === $entity) {
            $entity = new MediaObjectImage();

            $file = new File($filepath);
            $entity->setFile($file);
            
            if(!is_array($data)) {
                
                return null;
            }
        }
        $entity = $this->hydrate($data, $entity, $locale);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }

        return $entity;
    }

    public function hydrate($data, $entity, $locale = 'fr')
    {
       
        
        if (isset($data['name'])) {
            $entity->setName($data['name']);
        }
        if (isset($data['alt'])) {
            $entity->setCaption($data['alt']);
        }
        if (isset($data['description'])) {
            $entity->setDescription($data['description']);
        }
        if (isset($data['filename'])) {
            $entity->setFilename($data['filename']);
        }
        
        if(isset($data['category']) && !empty($data['category'])){
            
            $array = explode( '\\', get_class($entity));
       
            $data['category']['type'] = [ "name" => end($array) ];
            $data['category'] = $this->categoryAction->create($data['category']);
            if(null === $data['category']) {
                throw new \Exception('Error form WebPageAction relation field category');
            }
            $entity->setCategory($data['category']);
        }

        if(isset($data['tags'])){
            foreach ($data['tags'] as $key => $category) {
                $array = explode( '\\', get_class($entity));
                $category['type'] = [ "name" => end($array) ];
                $category = $this->categoryAction->create($category);
                if(null === $category) {
                    throw new \Exception('Error form MediaImageAction relation field tags');
                }
                $entity->addTag($category);
            }
        }
        
        if (null !== $entity->getFile()) {
            $file = $entity->getFile();
            $originalFilename = null;
            if (empty($entity->getOriginalFilename())) {
                if (is_callable([$file, 'getClientOriginalName'])) {
                    $originalFilename = $file->getClientOriginalName();
                } else {
                    $originalFilename = $file->getFilename();
                }
            } else {
                $originalFilename = $entity->getOriginalFilename();
            }
            $encodingFormat = null;
            if (!empty($file->getMimeType())) {
                $encodingFormat = $file->getMimeType();
            }
            $dimensions = [];
            $dimensions = getimagesize($file->getPathname());
            
            $contentSize = 0;
            if (!empty($file->getSize())) {
                $contentSize = $file->getSize();
            }
            $format = null;
            if (!empty($dimensions) && $dimensions[1] > $dimensions[0]) {
                $format = '-vertical';
            }
            $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
            if (is_callable([$file, 'getClientOriginalName'])) {
                $filename = $file->getClientOriginalName();
            } else {
                $filename = $file->getFilename();
            }
            $entity->setFilename($filename);
            $entity->setDimensions($dimensions);
            $entity->setOriginalFilename($originalFilename);
            $entity->setFile($file);
            $entity->setEncodingFormat($encodingFormat);
            $entity->setContentSize($contentSize);
        }

        $name = null;
        if (empty($entity->getName())) {
            $basename = pathinfo($entity->getFilename(), PATHINFO_BASENAME);
            
            $name = $this->slugger->slug($basename)->lower()->toString();
            $name = ucwords(str_replace('-', ' ', $name));
            $entity->setName($name);
        }
        $alt = null;
        if (empty($entity->getCaption())) {
            $alt = $name;
            $entity->setCaption($alt);
        }

        return $entity;
    }

    public function extract($image)
    {
        if(!$image instanceof MediaObjectImage) {
            $image = $this->create($image);  
        }
        
        if(null === $image) {
            throw new \Exception('Error form MediaObjectImageAction create image');
        }

        return $image;
    }

}
