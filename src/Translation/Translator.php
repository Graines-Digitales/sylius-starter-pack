<?php

namespace App\Translation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Google\Cloud\Translate\V2\TranslateClient;

class Translator
{
    private $container;

    private $isEnabled = false;

    private $apiKey = null;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->isEnabled = false;
        if(!empty($this->container->getParameter('google_cloud_api_key'))) {
            $this->apiKey = $this->container->getParameter('google_cloud_api_key');
            $this->isEnabled = true;
        }
    }

    public function translate($chain, $locale)
    {
        if(!$this->isEnabled) {

            return $chain;
        } else {
            $translate = new TranslateClient([
                'key' => $this->apiKey
            ]);
            $result = $translate->translate($chain, [
                'target' => $locale
            ]);
             
            if(isset($result['text'])) {
                return $result['text'];
            }

            return $chain; 
        }
    }

    private function isHTML($string){
        return $string != strip_tags($string) ? true:false;
    }

}
