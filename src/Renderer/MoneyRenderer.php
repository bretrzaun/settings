<?php
namespace BretRZaun\Settings\Renderer;

use BretRZaun\Settings\Value\SimpleValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyRenderer extends NumberRenderer
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setRequired([
            'currency',
        ]);
    }

    public function render(SimpleValue $value): string
    {
        return parent::render($value).' '.$this->options['currency'];
    }
}

