<?php

namespace App\WebContent;


class Category extends AbstractWebContent
{
    public function updateSlug($entity)
    {
        $slug = $this->slugger->slug($entity->getName())->lower()->toString();
        
        $entity->setSlug($slug);

        return $entity;
    }
}
