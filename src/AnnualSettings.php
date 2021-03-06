<?php
namespace BretRZaun\Settings;

use BretRZaun\Settings\Value\AnnualValue;
use BretRZaun\Settings\Value\ValueInterface;

class AnnualSettings extends Settings
{
    public function getValueClass(): string
    {
        return AnnualValue::class;
    }

    public function add(ValueInterface $value)
    {
        if (!$value instanceOf AnnualValue) {
            throw new \InvalidArgumentException('Only instances of AnnualValue allowed here.');
        }
        if ($this->renderer) {
            $value->setRenderer($this->renderer);
        }
        $this->values[] = $value;
    }

    public function getByYearAndKey(int $year, int $key): ?AnnualValue
    {
        foreach($this->values as $value) {
            if ($value->getKey() == $key && $value->getYear() === $year) {
                return $value;
            }
        }
        return null;
    }

    public function findByYear(int $year): array
    {
        $result = [];
        foreach($this->values as $value) {
            if ($value->getYear() === $year) {
                $result[$value->getKey()] = $value;
            }
        }
        return $result;
    }

    public function findByKey($key): array
    {
        $result = [];
        foreach($this->values as $value) {
            if ($value->getKey() == $key) {
                $result[$value->getYear()] = $value;
            }
        }
        return $result;
    }

    public function getLastByYearAndKey(int $year, int $key): ?AnnualValue
    {
        $values = $this->findByKey($key);
        ksort($values);
        $values = array_reverse($values, true);
        foreach ($values as $y => $value)
        {
            if ($year >= $y) {
                return $value;
            }
        }
        return null;
    }
}