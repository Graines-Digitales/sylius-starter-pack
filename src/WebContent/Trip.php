<?php

namespace App\WebContent;

class Trip extends AbstractWebContent
{
    public function moreData($entity)
    {
        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }
        
        if (empty($entity->getTextResume())) {
            $resume  = $this->contentTools->shapeSpace_truncate_string_at_word(
                $entity->getDescription(), 350, ' ', ''
            );
            $entity->setTextResume(trim($resume));
        }
    }
}
