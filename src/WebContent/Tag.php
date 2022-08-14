<?php

namespace App\WebContent;


class Tag extends AbstractWebContent
{
    public function moreData($entity) 
    {
        if (empty($entity->getAlternativeHeadline())) {
            $entity->setAlternativeHeadline($entity->getHeadline());
        }
        if (empty($entity->getArticleResume())) {
            $resume = strip_tags($entity->getArticleBody());
            $resume = substr($resume, 0, 350);
            $resume = html_entity_decode($resume, ENT_QUOTES);
            $entity->setArticleResume(trim($resume));
        }
    }
}
