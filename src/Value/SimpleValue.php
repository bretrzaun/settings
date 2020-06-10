<?php
namespace BretRZaun\Settings\Value;

class SimpleValue implements ValueInterface
{
    protected $key;
    protected $value;
    private $renderer;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function createFromArray(array $array): ValueInterface
    {
        return new self($array['key'], $array['value']);
    }

    public function setRenderer($renderer): void
    {
        $this->renderer = $renderer;
    }

    public function getRenderedValue(): string
    {
        return $this->renderer->render($this);
    }

    public function toArray(): array
    {
        return ['key' => $this->key, 'value' => $this->value];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}