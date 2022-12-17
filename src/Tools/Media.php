<?php

namespace App\Tools;

use App\Entity\Category;
use App\Entity\MediaObjectIcon;
use App\WebContent\SEO;
use App\Entity\MediaObjectImage;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
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
    
    private $cacheManager;

    public function __construct(
        Filesystem $filesystem
        , ContainerInterface $container
        , SluggerInterface $slugger
        , SEO $webContentSEOService
        , FilterService $imagine
        , CacheManager $cacheManager
    ) {

        $this->filesystem = $filesystem;
        $this->container = $container;
        $this->webContentSEOService = $webContentSEOService;
        $this->slugger = $slugger;
        $this->imagine = $imagine;
        $this->cacheManager = $cacheManager;
    }


     

    public function getMediaArray($manager)
    {
        $results = $manager->getRepository(MediaObjectImage::class)->findAll();
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
            MediaObjectImage::class, 'file'
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
    public function removeIcon($media)
    {
        $folderUploadedImages = $this->container->getParameter('path_directory_icon');
        $filename = $media->getOriginalFilename();
        $uploadedFilePath = $folderUploadedImages . DIRECTORY_SEPARATOR . $filename;
  
        $this->filesystem->remove($uploadedFilePath);
    }

    public function remove($media)
    {
        $folderUploadedImages = $this->container->getParameter('path_directory_media');
        $filename = $media->getOriginalFilename();
        $uploadedFilePath = $folderUploadedImages . DIRECTORY_SEPARATOR . $filename;
    
        $this->cacheManager->remove($filename);
        $this->cacheManager->remove($filename . '.webp');
        $this->filesystem->remove($uploadedFilePath);
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
