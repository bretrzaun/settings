<?php
namespace BretRZaun\Settings\Storage;

use BretRZaun\Settings\SettingsInterface;

interface StorageInterface
{
    public function load(string $settingsClass): SettingsInterface;
    public function save(SettingsInterface $settings): void;
}