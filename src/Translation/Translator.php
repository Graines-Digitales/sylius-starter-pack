<?php

namespace App\Translation;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Google\Cloud\Translate\V2\TranslateClient;

class Translator
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function translate()
    {
        $translate = new TranslateClient([
            'key' => 'your_key'
        ]);
        $result = $translate->translate('Hello world!', [
            'target' => 'fr'
        ]);
        dump('iciiii');die;
    }

}
