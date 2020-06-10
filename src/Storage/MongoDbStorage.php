<?php
namespace BretRZaun\Settings\Storage;

use MongoDB\Collection;
use BretRZaun\Settings\Settings;
use BretRZaun\Settings\SettingsInterface;

class MongoDbStorage implements StorageInterface
{

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    public function load(string $settingsClass): SettingsInterface
    {
        $result = $this->collection->find(
            ['class' => $settingsClass],
            ['typeMap' => ['root' => 'array']]
        );

        $settings = new $settingsClass;
        foreach ($result as $element) {
            $valueClass = $settings->getValueClass();
            $value = $valueClass::createFromArray($element);
            $settings->add($value);
        }
        return $settings;
    }

    public function save(SettingsInterface $settings): void
    {
        $this->createIndex();
        $documents = [];
        foreach($settings as $value) {
            $data = (array)$value->toArray();
            $data['class'] = get_class($settings);
            $documents[] = $data;
        }
        $this->collection->insertMany($documents);
    }

    protected function createIndex(): void
    {
        $this->collection->createIndex(
            ['class' => 1, 'key' => 1],
            ['unique' => true]
        );
    }

    public function updateValue(SettingsInterface $settings, $key): void
    {
        $value = $settings[$key];
        $document = $value->toArray();
        $document['class'] = get_class($settings);

        $filter = ['class' => get_class($settings), 'key' => $value->getKey()];
        $this->collection->replaceOne($filter, $document, ['upsert' => true]);
    }

    public function removeValue(SettingsInterface $settings, $key): void
    {
        $value = $settings[$key];
        $filter = ['class' => get_class($settings), 'key' => $value->getKey()];
        $this->collection->deleteOne($filter);
    }
}