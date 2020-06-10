<?php
namespace BretRZaun\Settings;

use BretRZaun\Settings\Value\ValueInterface;

interface SettingsInterface extends \Traversable, \ArrayAccess
{
    public function add(ValueInterface $value);
    public function removeElement(ValueInterface $value);
    public function toArray(): array;
    public function getValueClass(): string;
}