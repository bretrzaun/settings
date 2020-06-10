<?php
namespace BretRZaun\Settings\Value;

class AnualValue extends SimpleValue
{
    private $year;

    public function __construct($key, $value, int $year)
    {
        parent::__construct($key, $value);
        $this->year = $year;
    }

    public static function createFromArray(array $array): ValueInterface
    {
        return new self($array['key'], $array['value'], $array['year']);
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function toArray(): array
    {
        $array = parent::toArray();
        $array['year'] = $this->year;
        return $array;
    }
}