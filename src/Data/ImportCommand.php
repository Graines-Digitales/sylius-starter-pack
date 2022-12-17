<?php

namespace App\Data;

use App\Data\Import;
use App\Entity\Category;
use App\Entity\MediaObjectIcon;
use App\Entity\MediaObjectImage;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;


class ImportCommand extends Import
{
    public function fromImagesFolder($io)
    {
        $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
        $configurationProject = $this->container->getParameter('configuration_project');
        $imageMimeTypes = $configurationProject['media_encoding_formats']['image'];

        $imagesPath = $kernelProjectDir . '/content/images';
        $svgsPath = $kernelProjectDir . '/content/svgs';

        $filesystem = new Filesystem();
        if($filesystem->exists($imagesPath)) {
            $finder = new Finder();
            $finder->files()->in($imagesPath);
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $dirname = pathinfo(pathinfo($file->getRealPath(), PATHINFO_DIRNAME), PATHINFO_BASENAME);
                    $filename = pathinfo($file->getRelativePathname(), PATHINFO_FILENAME);
                    $basename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                    $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                    $category = null;
                    $data = [];
                    if('images' !== $dirname) {
                        $d['name'] = $this->slugger->slug($dirname)->lower()->toString();
                        $category = $this->entityManager->getRepository(Category::class)
                            ->findOneBySlug($d['name']);
                            
                        if(null === $category) {
                            
                            $category = $this->categoryAction->create($d);
                        }
                        $data['category']['name'] = $category;

                        
                        if (str_contains($filename, '-')) { 
                            $items = explode("-", $filename);
                        
                        }
                        $d['name'] = $this->slugger->slug(trim($items[1]))->lower()->toString();
                        $category = $this->entityManager->getRepository(Category::class)
                            ->findOneBySlug($d['name']);
                            
                        if(null === $category) {
                            
                            $category = $this->categoryAction->create($d);
                        }
                        $data['tags'][0]['name'] = $category;
                        
                    }

                    $file = new File($absoluteFilePath);
                    
                    if (in_array($file->getMimeType(), $imageMimeTypes)) {
                    
                        $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/image/' . $basename);
                        $entity = $this->entityManager->getRepository(MediaObjectImage::class)
                        ->findOneBy([ 'filename' => $basename ]);
                        $data['filename'] = $basename;
                        if(null === $entity) {
                            
                            $entity = $this->mediaImageService->create($data);
                        } else {
                            $entity = $this->mediaImageService->hydrate($data, $entity);
                        }
                        $this->entityManager->persist($entity);
                    }
                }
                $this->entityManager->flush();
            }
        }

        $filesystem = new Filesystem();
        if($filesystem->exists($svgsPath)) {
            $finder = new Finder();
            $finder->files()->in($svgsPath);
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                    $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                    $file = new File($absoluteFilePath);
                    $filesystem->copy($absoluteFilePath, $kernelProjectDir .  '/public/media/icon/' . $filename);
                    $entity = $this->entityManager->getRepository(MediaObjectIcon::class)
                    ->findOneBy([ 'filename' => $filename ]);
                    $data = [];
                    $data['filename'] = $filename;
                    if(null === $entity) {
                        
                        $entity = $this->mediaIconService->create($data);
                    } else {
                        $entity = $this->mediaIconService->hydrate($data, $entity);
                    }
                    $this->entityManager->persist($entity);
                }
                $this->entityManager->flush();
            }
        }
    }

    public function fromContentFolder($io)
    {
        $categoryRoot = $this->categoryAction->create(["name" => "Root"]);

        $locales = $this->container->get('sylius.repository.locale')->findAll();
     
        foreach ($locales as $locale) {
            $localeCode = $locale->getCode();
            // dump($localeCode);die;
            // $locale = current(explode('_', $localeCode));

            $kernelProjectDir = $this->container->getParameter('kernel.project_dir');
            $contentPath = $kernelProjectDir . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . $localeCode;
            $filesystem = new Filesystem();
            if ('fr' === $localeCode) {
                if ($filesystem->exists($contentPath)) {
                    $finder = new Finder();
                    $finder->depth('<= 1');
                    $finder->files()->in($contentPath);
                    if ($finder->hasResults()) {
                        foreach ($finder as $file) {
                            $absoluteFilePath = $file->getRealPath();
                            $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
                            $filename = pathinfo($file->getRelativePathname(), PATHINFO_BASENAME);
                            $dirname = pathinfo($file->getRelativePathname(), PATHINFO_DIRNAME);
                            $io->info('Fichier trouvé : ' . $localeCode . ' ' . $dirname . ' ' .  $filename);

                            $data = $this->extractData($absoluteFilePath, $extension);
                            if (empty($data)) {
                                continue;
                                $io->error('Un fichier vide a été trouvé : ' .$dirname . ' ' .  $filename);
                                die;
                            }
                            if ('.' !== $dirname) {
                                $entity = $this->dataServicesDispatch($data, $dirname, $localeCode);

                                if (empty($entity)) {
                                    $io->error('Une erreur est survenue :' . $dirname . ' ' . $filename);
                                    die;
                                }
                                $this->entityManager->persist($entity);
                                $this->entityManager->flush();
                            }
                        }
                    }
                }
            }
        }


        // $locales = $this->container->get('sylius.repository.locale')->findAll();
     
        // foreach($locales as $locale) {
        //     $localeCode = $locale->getCode();
        //     $locale = current(explode('_', $localeCode));
        //     if('fr' === $locale) {
                
               
        //         foreach($folders as $folder) {
        //             $finder = new Finder();
        //             $path = $contentPath . DIRECTORY_SEPARATOR . $locale . DIRECTORY_SEPARATOR . $folder;
        //             $finder->depth('== 0');
        //             $finder->files()->in($path);
        //             if ($finder->hasResults()) {
        //                 foreach ($finder as $file) {
        //                     $absoluteFilePath = $file->getRealPath();
        //                     $extension = pathinfo($file->getRelativePathname(), PATHINFO_EXTENSION);
        //                     $data = $this->extractData($absoluteFilePath, $extension);
        //                     $entity = $this->dataServicesDispatch($data, $folder);
        //                     $this->entityManager->persist($entity);
        //                 }
        //                 $this->entityManager->flush();
        //             }
        //         }
        //     }
        // }
    }

}
