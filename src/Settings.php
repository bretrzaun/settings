<?php
namespace BretRZaun\Settings;

use BretRZaun\Settings\Value\ValueInterface;
use BretRZaun\Settings\Value\SimpleValue;

class Settings implements SettingsInterface, \IteratorAggregate, \JsonSerializable
{
    protected $values = [];
    protected $renderer;

    public function __construct(array $values = [], $renderer = null)
    {
        $this->renderer = $renderer;
        foreach ($values as $value) {
            $this->add($value);
        }
    }

    public function add(ValueInterface $value)
    {
        if ($this->renderer) {
            $value->setRenderer($this->renderer);
        }
        $this->values[$value->getKey()] = $value;
    }

    public function removeElement(ValueInterface $value)
    {
        $key = array_search($value, $this->values, true);
        if ($key !== false) {
            unset($this->values[$key]);
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function offsetSet($offset, $value)
    {
        $this->add($value);
    }

    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->values[$offset] ?? null;
    }

    public function jsonSerialize ()
    {
        return array_values($this->values);
    }

    public function getValueClass(): string
    {
        return SimpleValue::class;
    }
}