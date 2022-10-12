<?php

namespace App\Data\Action;

use App\Entity\MediaObjectIcon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class MediaObjectIconAction
{
    private $slugger;

    private $entityManager;

    private $container;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , ContainerInterface $container
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(MediaObjectIcon::class)
                ->findOneBySlug($data)
            ;
        }

        $filename = (isset($data['filename']))? $data['filename']: null;

        $directoryProject = $this->container->getParameter('kernel.project_dir');
        $folderImage = $this->container->getParameter('directory_icon_media_object');
        $filepath = $directoryProject . DIRECTORY_SEPARATOR . 'public' .  $folderImage;
        $filepath.= DIRECTORY_SEPARATOR . $filename;
        $filesystem = new Filesystem();
        if(!$filesystem->exists($filepath)) {
            throw new \Exception(sprintf('Error form MediaObjectIconAction filepath icon %s', $filepath));
        }

        $entity = $this->entityManager->getRepository(MediaObjectIcon::class)
            ->findOneBy(['filename' => $filename ])
        ;
        if(null === $entity) {
            $entity = new MediaObjectIcon();

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

    public function hydrate($data, $entity, $locale)
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
        if (isset($data['html'])) {
            $entity->setHtml($data['html']);
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
            
            
            $contentSize = 0;
            if (!empty($file->getSize())) {
                $contentSize = $file->getSize();
            }
            
            $filename = pathinfo($originalFilename, PATHINFO_FILENAME);
            if (is_callable([$file, 'getClientOriginalName'])) {
                $filename = $file->getClientOriginalName();
            } else {
                $filename = $file->getFilename();
            }
            $entity->setFilename($filename);
            $entity->setOriginalFilename($originalFilename);
            $entity->setFile($file);
            $entity->setEncodingFormat($encodingFormat);
            $entity->setContentSize($contentSize);
        } 

        $name = null;
        if (empty($entity->getName())) {
            $name = $this->slugger->slug($entity->getFilename())->lower()->toString();
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
        if(!$image instanceof MediaObjectIcon) {
            $image = $this->create($image);  
        }
        
        if(null === $image) {
            throw new \Exception('Error form MediaObjectImageAction create image');
        }

        return $image;
    }

}
