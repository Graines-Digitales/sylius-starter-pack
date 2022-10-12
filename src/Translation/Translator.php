<?php

namespace App\Translation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Google\Cloud\Translate\V2\TranslateClient;

class Translator
{
    private $container;

    private $isEnabled = false;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->isEnabled = false;
        if(!empty($this->container->getParameter('google_cloud_api_key'))) {
            $this->isEnabled = true;
        }
    }

    public function translate($chain)
    {
        if(!$this->isEnabled) {

            return $chain;
        } else {
            dump('attention mon ami!');die;
        }
        $translate = new TranslateClient([
            'key' => 'your_key'
        ]);
        $result = $translate->translate('Hello world!', [
            'target' => 'fr'
        ]);
    }

    private function isHTML($string){
        return $string != strip_tags($string) ? true:false;
    }

}
