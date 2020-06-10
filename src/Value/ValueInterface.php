<?php
namespace BretRZaun\Settings\Value;

interface ValueInterface extends \JsonSerializable
{
    public function getKey();
    public function getValue();
    public static function createFromArray(array $array): ValueInterface;
    public function toArray(): array;
}