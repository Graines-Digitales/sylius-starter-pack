<?php

namespace App\Tools;

use App\WebContent\SEO;
use App\Entity\ImageMediaObject;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Filesystem\Filesystem;
use Liip\ImagineBundle\Service\FilterService;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Media
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
    * @var array
    */
    private $parameters;

    /**
     * @var Slugger
     */
    private $slugger;

    /**
     * Undocumented variable
     *
     * @var [FilterService]
     */
    private $imagine;

    public function __construct(
        Filesystem $filesystem
        , ContainerInterface $container
        , SluggerInterface $slugger
        , SEO $webContentSEOService
        , FilterService $imagine
    ) {

        $this->filesystem = $filesystem;
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->slugger = $slugger;
        $this->imagine = $imagine;
    }

    public function defineEntityMediaFromFile($entity)
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $videoMimeTypes = $configurationProject['media_encoding_formats']['video'];
        $imageMimeTypes = $configurationProject['media_encoding_formats']['image'];
        if (in_array($entity->getEncodingFormat(), $videoMimeTypes)) {
            $filename = pathinfo($entity->getUrl(), PATHINFO_FILENAME);
            if (empty($entity->getName())) {
                $filename = $this->slugger->slug($filename)->lower()->toString();
                $filename = ucwords(str_replace('-', ' ', $filename));
                $entity->setName($filename);
            }
            $entity->setFilename($filename);
        } else {
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
                if (in_array($encodingFormat, $imageMimeTypes)) {
                    $dimensions = getimagesize($file->getPathname());
                }
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
                $name = $this->slugger->slug($entity->getFilename())->lower()->toString();
                $name = ucwords(str_replace('-', ' ', $name));
                $entity->setName($name);
            }
            $alt = null;
            if (empty($entity->getCaption())) {
                $alt = $this->webContentSEOService->defineAltImage($entity);
                $entity->setCaption($alt);
            }
        }

        return $entity;
    }

    public function getMediaArray($manager)
    {
        $results = $manager->getRepository(ImageMediaObject::class)->findAll();
        $medias = [];
        foreach ($results as $key => $value) {
            $medias[$value->getFilename()] = $value;
        }

        return $medias;
    }

    public function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1000));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1000, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    public function getMimetypesRules()
    {
        $annotationEntityService = $this->container->get(
            'app.tools.annotation'
        );
        $constraints = $annotationEntityService->getContraintsByField(
            ImageMediaObject::class, 'file'
        );

        $mimeTypes = [];
        foreach ($constraints as $constraint) {
            if ($constraint instanceof File) {
                $mimeTypes = $constraint->mimeTypes;
            }
        }

        return $mimeTypes;
    }



    public function defineName($media)
    {
        if (empty($media->getName())) {
            $name = pathinfo($media->getFilename(), PATHINFO_FILENAME);
            $name = $this->slugger->slug($name);

            return $name;
        }

        return $media->getName();
    }

    public function defineUrl($media)
    {
        $cdnHost = null;
        $assetsMediaFolder = $this->container->getParameter(
              'uploads.media.folder'
        );

        $url = $media->getUrl();
        if ('video/youtube' !== $media->getEncodingFormat()) {
            return $cdnHost.$assetsMediaFolder.'/'.$media->getFilename();
        }

        return $url;
    }

    public function remove($media)
    {
      $webpFilename = pathinfo($media->getFilename(), PATHINFO_FILENAME).'.'.'webp';
      $webpFileCachePath = $this->parameters['assetsUploadsMediaFolder'].'/'.$webpFilename;

      $fileCachePath = $this->parameters['assetsUploadsMediaFolder'].'/'.$media->getFilename();
      $fileUploadsMediaPath = $this->parameters['assetsUploadsMediaFolderPath'].'/'.$media->getFilename();

      $this->cacheManager->remove($fileCachePath);
      $this->cacheManager->remove($webpFileCachePath);
      $this->filesystem->remove($fileUploadsMediaPath);
    }

    public function checkIfFileAlreadyUploaded($filename)
    {
      $targetFilePath = $this->parameters['assetsRootPath'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $this->parameters['assetsUploadsMediaFolder'];
      $targetFilePath.= DIRECTORY_SEPARATOR . $filename;
      if($this->filesystem->exists($targetFilePath)) {

          return true;
      }

      return false;
    }

    public function copyFileToUploadsFolder($filename, $sourceFilePath)
    {

        $targetFilePath = $this->parameters['assetsRootPath'];
        $targetFilePath.= DIRECTORY_SEPARATOR . $this->parameters['assetsUploadsMediaFolder'];
        $targetFilePath.= DIRECTORY_SEPARATOR . $filename;

        if(!$this->filesystem->exists($targetFilePath)) {
            $this->filesystem->copy($sourceFilePath, $targetFilePath);
        }
    }

    private function defineParameters()
    {
      $assetsRootPath = $this->container->getParameter('assets.path');
      if (!$this->filesystem->exists($assetsRootPath)) {
          $this->filesystem->mkdir($assetsRootPath);
      }
      $assetsUploadsMediaFolder = $this->container->getParameter('uploads.media.folder');
      $assetsUploadsMediaFolderPath = $assetsRootPath.DIRECTORY_SEPARATOR;
      $assetsUploadsMediaFolderPath .= $assetsUploadsMediaFolder.DIRECTORY_SEPARATOR;
      $assetsCachePrefix = $this->container->getParameter('cache_prefix');

      return [
          'assetsRootPath' => $assetsRootPath,
          'assetsUploadsMediaFolder' => $assetsUploadsMediaFolder,
          'assetsUploadsMediaFolderPath' => $assetsUploadsMediaFolderPath,
          'assetsCachePrefix' => $assetsCachePrefix
      ];
    }

    private function compressFileInWebpFormat($sourceFilePath, $targetFilePath, $quality = 70)
    {
        $cmd = [
            '/usr/bin/cwebp',
            '-q',
            $quality,
            $sourceFilePath,
            '-o',
            $targetFilePath,
        ];
        $process = new Process($cmd);
        $process->setTimeout(900);
        $process->mustRun();
    }

    public function getIcons()
    {
        $finder = new Finder;
        $icons = [];
        $path = $this->container->getParameter('path_directory_icon');
        if($this->filesystem->exists($path)) {
            $finder->depth('== 0');
            $finder->files()->in($path);
            if ($finder->hasResults()) {
                foreach ($finder as $file) {
                    $absoluteFilePath = $file->getRealPath();
                    $filePath = $file->getPath();
                    $fileNameWithExtension = $file->getRelativePathname();
                    $ext = pathinfo($fileNameWithExtension, PATHINFO_EXTENSION);
                    $filename = pathinfo($fileNameWithExtension,  PATHINFO_FILENAME);
                    $icons[$fileNameWithExtension] = $filename;
                }
            }

        }

        return $icons;
    }

    public function generateFiltersForMediaObject($media)
    {
        $filter_sets = $this->container->getParameter(
            'liip_imagine.filter_sets'
        );
        foreach($filter_sets as $filterName => $filter) {
            $pattern='/sylius|monsieurbiz/i';
            if (!preg_match($pattern, $filterName) ) {
                $this->imagine->getUrlOfFilteredImage($media->getFilename(), $filterName);
            }
        }

        return true;
    }

    public function generateFiltersForProduct($media)
    {
        $filter_sets = $this->container->getParameter(
            'liip_imagine.filter_sets'
        );
        foreach($filter_sets as $filterName => $filter) {
            // $pattern='/sylius/i';
            $pattern='/sylius|monsieurbiz/i';

            if (!preg_match($pattern, $filterName) ) {

                $this->imagine->getUrlOfFilteredImage($media->getPath(), $filterName);
            }
        }
    }
}
