<?php

namespace App\WebContent;


class Article extends AbstractWebContent
{
    public function moreData($entity) 
    {
        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }

        if (empty($entity->getArticleResume())) {
            
            $resume = $this->contentTools->shapeSpace_truncate_string_at_word(
                $entity->getArticleBody(), 350, ' ', ''
            );
            $entity->setArticleResume(trim($resume));
        }
    }
}
