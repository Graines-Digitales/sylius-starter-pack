<?php

namespace App\Data;

use App\Entity\Component;
use Symfony\Component\String\Slugger\SluggerInterface;


class ComponentAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function create($data = [], $locale = 'fr_FR')
    {
        $component = new Component();
        $component = $this->hydrate($data, $component, $locale);
        
        return $component;
    }
    
    public function hydrate($data = [], $component, $locale)
    {
        if(isset($data['name'])) {
            $component->setName($data['name']);
            $component->getTranslation($locale)->setHeadline($data['name']);
        }

        if (isset($data['components'])) {
            $components = [];
            
            foreach($data['components'] as $result) {
                $array = [];
                $array['code'] = $result['code'];
                unset($result['code']);
                $array['data'] = $result;
                array_push($components, $array);
            }
            
            $component->getTranslation($locale)->setComponents(
                json_encode($components)
            );
        }

        return $component;
    }

}
