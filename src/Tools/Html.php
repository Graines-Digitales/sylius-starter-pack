<?php

namespace App\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Html
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function isHTML($string)
    {
        return $string != strip_tags($string) ? true : false;
    }
}
