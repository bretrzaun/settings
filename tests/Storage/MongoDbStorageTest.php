<?php
namespace BretRZaun\Settings\Test\Storage;

use PHPUnit\Framework\TestCase;
use BretRZaun\Settings\Storage\MongoDbStorage;
use BretRZaun\Settings\Value\SimpleValue;
use BretRZaun\Settings\Settings;
use BretRZaun\Settings\Value\AnualValue;
use BretRZaun\Settings\AnualSettings;

class MongoDbStorageTests extends TestCase
{
    public function testLoad(): void
    {
        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('find')
            ->with(['class' => Settings::class])
            ->willReturn([
                ['key' => 1, 'value' => 'First', 'class' => Settings::class],
                ['key' => 2, 'value' => 'Second', 'class' => Settings::class]
            ])
            ;

        $storage = new MongoDbStorage($collection);
        $settings = $storage->load(Settings::class);

        $this->assertCount(2, $settings);
        $this->assertEquals(1, $settings[1]->getKey());
        $this->assertEquals('First', $settings[1]->getValue());
    }

    public function testLoadAnual(): void
    {
        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('find')
            ->with(['class' => AnualSettings::class])
            ->willReturn([
                ['key' => 1, 'value' => 'First', 'year' => 2019, 'class' => AnualSettings::class],
                ['key' => 2, 'value' => 'Second', 'year' => 2020, 'class' => AnualSettings::class]
            ])
            ;

        $storage = new MongoDbStorage($collection);
        $settings = $storage->load(AnualSettings::class);

        $this->assertCount(2, $settings);
        $this->assertEquals(1, $settings->getByYearAndKey(2019, 1)->getKey());
        $this->assertEquals(2019, $settings->getByYearAndKey(2019, 1)->getYear());
        $this->assertEquals('First', $settings->getByYearAndKey(2019, 1)->getValue());
    }

    public function testSave(): void
    {
        $first = new SimpleValue(1, 'First');
        $second = new SimpleValue(2, 'Second');
        $settings = new Settings([$first, $second]);

        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('insertMany')
            ->with([
                ['key' => 1, 'value' => 'First', 'class' => Settings::class],
                ['key' => 2, 'value' => 'Second', 'class' => Settings::class]
            ])
            ;

        $storage = new MongoDbStorage($collection);
        $storage->save($settings);
    }

    public function testSaveAnual(): void
    {
        $first = new AnualValue(1, 'First', 2019);
        $second = new AnualValue(2, 'Second', 2020);
        $settings = new AnualSettings([$first, $second]);

        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('insertMany')
            ->with([
                ['key' => 1, 'value' => 'First', 'year' => 2019, 'class' => AnualSettings::class],
                ['key' => 2, 'value' => 'Second', 'year' => 2020, 'class' => AnualSettings::class]
            ])
            ;

        $storage = new MongoDbStorage($collection);
        $storage->save($settings);
    }

    public function testUpdateValue(): void
    {
        $second = new SimpleValue(2, 'changedSecond');
        $settings = new Settings([$second]);

        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('replaceOne')
            ->with(
                ['class' => Settings::class, 'key' => 2],
                ['key' => 2, 'value' => 'changedSecond', 'class' => Settings::class]
            )
        ;

        $storage = new MongoDbStorage($collection);
        $storage->updateValue($settings, 2);
    }

    public function testRemoveValue(): void
    {
        $second = new SimpleValue(2, 'Second');
        $settings = new Settings([$second]);

        $collection = $this->createMock(\MongoDB\Collection::class);
        $collection->expects($this->once())
            ->method('deleteOne')
            ->with(
                ['class' => Settings::class, 'key' => 2],
            )
        ;

        $storage = new MongoDbStorage($collection);
        $storage->removeValue($settings, 2);
    }
}