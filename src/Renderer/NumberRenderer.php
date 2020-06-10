<?php
namespace BretRZaun\Settings\Renderer;

use BretRZaun\Settings\Value\SimpleValue;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumberRenderer implements RendererInterface
{

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'decimals' => 2,
            'dec_point' => '.',
            'thousands_sep' => ','
        ]);
    }

    public function render(SimpleValue $value): string
    {
        return number_format(
            $value->getValue(),
            $this->options['decimals'],
            $this->options['dec_point'],
            $this->options['thousands_sep']
        );
    }
}

