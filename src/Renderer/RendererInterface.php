<?php
namespace BretRZaun\Settings\Renderer;

use BretRZaun\Settings\Value\SimpleValue;

interface RendererInterface
{
    public function render(SimpleValue $value): string;
}