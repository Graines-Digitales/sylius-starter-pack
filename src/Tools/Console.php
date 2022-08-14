<?php

namespace App\Tools;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Console
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function transposeKeyValue($array)
    {
        $newArray = [];
        foreach ($array as $key => $value) {
            $newArray[] = [$key, $value];
        }

        return $newArray;
    }
}
