<?php
namespace BretRZaun\Settings\Storage;

use BretRZaun\Settings\Settings;
use BretRZaun\Settings\SettingsInterface;
use BretRZaun\Settings\Value\SimpleValue;

class FileStorage implements StorageInterface
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function load(string $settingsClass): SettingsInterface
    {
        $content = file_get_contents($this->filename);
        $json = json_decode($content, true);
        $settings = new $settingsClass;
        foreach ($json['data'] as $element) {
            $valueClass = $settings->getValueClass();
            $value = $valueClass::createFromArray($element);
            $settings->add($value);
        }
        return $settings;
    }

    public function save(SettingsInterface $settings): void
    {
        $data = [
            'data' => $settings
        ];
        $json = json_encode($data);
        file_put_contents($this->filename, $json);
    }
}