<?php

namespace App\Data;

use App\Tools\Media;
use Mni\FrontYAML\Parser;
use App\Data\Action\RoomAction;
use App\Data\Action\TripAction;
use App\Data\Action\ArticleAction;
use App\Data\Action\WebPageAction;
use App\Data\Action\CategoryAction;
use App\Data\Action\ComponentAction;
use App\Data\Action\HotelServiceAction;
use App\Data\Action\OrganizationAction;
use App\Data\Action\HotelActivityAction;
use Doctrine\ORM\EntityManagerInterface;
use App\Data\Action\AmenityFeatureAction;
use App\Data\Action\HotelTypicalDayAction;
use App\Data\Action\HotelTypicalDayElementAction;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Import
{
    protected $container;

    protected $entityManager;

    protected $webPageAction;

    protected $articleAction;

    protected $componentAction;

    protected $tripAction;

    protected $organizationAction;

    protected $mediaService;

    protected $hotelActivityAction;

    protected $hotelServiceAction;

    protected $amenityFeatureAction;

    protected $hotelTypicalDayAction;

    protected $hotelTypicalDayElementAction;

    protected $roomAction;

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
        , HotelActivityAction $hotelActivityAction
        , HotelServiceAction $hotelServiceAction
        , AmenityFeatureAction $amenityFeatureAction
        , HotelTypicalDayAction $hotelTypicalDayAction
        , HotelTypicalDayElementAction $hotelTypicalDayElementAction
        , RoomAction $roomAction
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
        $this->hotelActivityAction = $hotelActivityAction;
        $this->hotelServiceAction = $hotelServiceAction;
        $this->amenityFeatureAction = $amenityFeatureAction;
        $this->hotelTypicalDayAction = $hotelTypicalDayAction;
        $this->hotelTypicalDayElementAction = $hotelTypicalDayElementAction;
        $this->roomAction = $roomAction;
    }

    public function getMainOrganization($contentPath)
    {
        $filesystem = new Filesystem();
        $filepath = $contentPath . DIRECTORY_SEPARATOR . 'main_organization.json';
        if($filesystem->exists($filepath)) {

            $result = $this->extractData($filepath, 'json');
            
            return $result;
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
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }
               
                return $this->categoryAction->create($data);
              
                break;
            case 'web_pages':
               
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['text'] = $data['content'];
                    unset($data['content']);
                }

                return $this->webPageAction->create($data);
                
                break;
            case 'articles':
                
                /** hack markdown file */
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
                
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->organizationAction->create($data);
                
                break;
            case 'travels':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->tripAction->create($data);
                
                break;
            case 'hotel_activities':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->hotelActivityAction->create($data);
                
                break;
            case 'hotel_services':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->hotelServiceAction->create($data);
                
                break;
            case 'hotel_amenities':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->amenityFeatureAction->create($data);
                
                break;
            case 'hotel_typical_days':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->hotelTypicalDayAction->create($data);
                
                break;
            case 'hotel_typical_day_elements':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }

                return $this->hotelTypicalDayElementAction->create($data);
                
                break;
            case 'rooms':
                /** hack markdown file */
                if(isset($data['content'])) {
                    $data['description'] = $data['content'];
                    unset($data['content']);
                }


                return $this->roomAction->create($data);
                
                break;
                
        }

        return null;
    }
}
