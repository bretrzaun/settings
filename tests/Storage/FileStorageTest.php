<?php
namespace BretRZaun\Settings\Test\Storage;

use PHPUnit\Framework\TestCase;
use BretRZaun\Settings\Storage\FileStorage;
use BretRZaun\Settings\Value\SimpleValue;
use BretRZaun\Settings\Settings;
use BretRZaun\Settings\Value\AnualValue;
use BretRZaun\Settings\AnualSettings;

class FileStorageTests extends TestCase
{
    public function testLoad(): void
    {
        $storage = new FileStorage(__DIR__.'/../fixtures/settings01.json');
        $settings = $storage->load(Settings::class);

        $this->assertCount(2, $settings);
        $this->assertEquals(1, $settings[1]->getKey());
        $this->assertEquals('First', $settings[1]->getValue());
    }

    public function testLoadAnual(): void
    {
        $storage = new FileStorage(__DIR__.'/../fixtures/anualsettings01.json');
        $settings = $storage->load(AnualSettings::class);

        $this->assertInstanceOf(AnualSettings::class, $settings);
        $this->assertCount(2, $settings);
        $this->assertInstanceOf(AnualValue::class, $settings->getByYearAndKey(2019, 1));
        $this->assertEquals(1, $settings->getByYearAndKey(2019, 1)->getKey());
        $this->assertEquals('Second', $settings->getByYearAndKey(2020, 2)->getValue());
    }

    public function testSave(): void
    {
        $first = new SimpleValue(1, 'First');
        $second = new SimpleValue(2, 'Second');
        $settings = new Settings([$first, $second]);

        $storage = new FileStorage(__DIR__.'/../fixtures/settings02.json');
        $storage->save($settings);
        $this->assertJsonFileEqualsJsonFile(
            __DIR__.'/../fixtures/settings01.json',
            __DIR__.'/../fixtures/settings02.json'
        );
    }
}
