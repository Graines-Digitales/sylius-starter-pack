<?php

namespace App\Data\Action;

use App\Entity\Component;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class ComponentAction
{
    private $slugger;
    
    private $entityManager;

    private $mediaObjectImageAction;

    private $mediaObjectIconAction;

    public function __construct(
        SluggerInterface $slugger
        , EntityManagerInterface $entityManager
        , MediaObjectImageAction $mediaObjectImageAction
        , MediaObjectIconAction $mediaObjectIconAction
    ){
        $this->slugger = $slugger;
        $this->entityManager = $entityManager;
        $this->mediaObjectImageAction = $mediaObjectImageAction;
        $this->mediaObjectIconAction = $mediaObjectIconAction;
    }

    public function create($data = [], $locale = 'fr', $persist = true)
    {
        if(!is_array($data)) {
            
            return $this->entityManager
                ->getRepository(Component::class)
                ->findOneBySlug($data)
            ;
        }

        $slug = (isset($data['slug']))? $data['slug']: $this->slugger->slug(str_replace("'", "", $data['name']))->lower()->toString();
        $entity = $this->entityManager->getRepository(Component::class)
            ->findOneBySlug($slug)
        ;
        // $entity = null;
        if(null === $entity) {
            $entity = new Component();
        }
        $entity = $this->hydrate($data, $entity, $locale);
        if($persist) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();
        }
        
        return $entity;
    }
    
    public function hydrate($data = [], $entity, $locale)
    {
        if(isset($data['name'])) {
            $entity->setName($data['name']);
            $entity->getTranslation($locale)->setHeadline($data['name']);
        }

        if (isset($data['components']) && !empty($data['components'])) {
            $components = $this->createComponents($data['components']);
            $entity->getTranslation($locale)->setComponents(
                json_encode($components, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)
            );
        }

        return $entity;
    }

    public function createComponents($data)
    {
        $data = (!is_array($data))? json_decode($data, true): $data;
        $components = [];
        foreach($data as $result) {
            $array = [];
            
            if(!isset($result['data'])) {
                $result['data'] = $result;
            }

            if(isset($result['data']['primaryImage']) && !empty($result['data']['primaryImage'])){
                $image = $this->mediaObjectImageAction->extract($result['data']['primaryImage']);
            }
            if(isset($result['data']['secondaryImage']) && !empty($result['data']['secondaryImage'])){
                $image = $this->mediaObjectImageAction->extract($result['data']['secondaryImage']);
            }
            if(isset($result['data']['icon']) && !empty($result['data']['icon'])){
                $icon = $this->mediaObjectIconAction->extract($result['data']['icon']);
            }

            $array['code'] = $result['code'];
            unset($result['code']);
            $array['data'] = $result['data'];
            array_push($components, $array);
        }
        
        return $components;
    }

    public function createComponentFromGallery($data)
    {
        $components = [];
        foreach($data['imageGalleries'] as $result) {
            $image = $this->mediaObjectImageAction->extract($result['image']);
            $array = [
                "code"=> "app.component_card",
                "data" => [
                    "_slug" => "",
                    "slug" => $this->slugger->slug($image->getFilename())->lower()->toString(),
                    "designation" => $image->getFilename(),
                    "title" => $image->getFilename(),
                    "subtitle" => null,
                    "category" => "",
                    "tags" => [],
                    "primaryImage" => [
                        "id" => $image->getId(),
                        "filename" => $image->getFilename(),
                        "alt" => $image->getCaption()
                    ],
                    "secondaryImage" => "",
                    "content" => $result['description'],
                    "icon" => [],
                    "links" => []
                ]
            ];         
            array_push($components, $array);
        }

        $component = [
            "code" => "app.component_cards",
            "data" => [
                "slug" => "component-cars-" . $this->slugger->slug($data['name'])->lower()->toString(),
                "designation" => $data['name'],
                "title" => $data['name'],
                "content" => $data['description'],
                "primaryImage" => "",
                "video"=> "",
                "cards" => $components
            ]
        ];

        return $component;
    }

}
