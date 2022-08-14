<?php

/*
 * This file is part of Monsieur Biz' Rich Editor plugin for Sylius.
 *
 * (c) Monsieur Biz <sylius@monsieurbiz.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\UiElement;

use MonsieurBiz\SyliusRichEditorPlugin\UiElement\UiElementInterface;
use MonsieurBiz\SyliusRichEditorPlugin\UiElement\UiElementTrait;


final class TestUiElement implements UiElementInterface
{
    use UiElementTrait;

    /**
     * @var String
     */
    private $section;

    /**
     * GoogleMapsUiElement constructor.
     *
     * @param $section
     */
    public function __construct($section)
    {
        $this->section = $section;
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->section;
    }
}
