<?php
namespace BretRZaun\Settings\Test;

use PHPUnit\Framework\TestCase;
use BretRZaun\Settings\Value\AnualValue;
use BretRZaun\Settings\AnualSettings;

/**
 * @group anual
 */
class AnualSettingsTests extends TestCase
{
    private function getAnualSettings(): AnualSettings
    {
        $first2018 = new AnualValue(1, 'First 2018', 2018);
        $second2018 = new AnualValue(2, 'Second 2018', 2018);
        $first2020 = new AnualValue(1, 'First 2020', 2020);
        $second2020 = new AnualValue(2, 'Second 2020', 2020);

        $settings = new AnualSettings([$first2018, $second2018, $first2020, $second2020]);
        return $settings;
    }

    public function testAdd(): void
    {
        $settings = $this->getAnualSettings();
        $this->assertCount(4, $settings);
    }

    public function testGetByYearAndKey(): void
    {
        $settings = $this->getAnualSettings();

        $this->assertEquals('First 2018', $settings->getByYearAndKey(2018, 1)->getValue());
        $this->assertEquals('Second 2020', $settings->getByYearAndKey(2020, 2)->getValue());
        $this->assertNull($settings->getByYearAndKey(2021, 1));
    }

    public function testFindByYear(): void
    {
        $settings = $this->getAnualSettings();

        $year2018 = $settings->findByYear(2018);
        $this->assertCount(2, $year2018);
        $this->assertEquals('First 2018', $year2018[1]->getValue());
    }

    public function testGetLastByYearAndKey(): void
    {
        $settings = $this->getAnualSettings();

        $this->assertNull($settings->getLastByYearAndKey(2017, 1));
        $this->assertEquals('First 2018', $settings->getLastByYearAndKey(2018, 1)->getValue());
        $this->assertEquals('First 2018', $settings->getLastByYearAndKey(2019, 1)->getValue());
        $this->assertEquals('Second 2020', $settings->getLastByYearAndKey(2020, 2)->getValue());
        $this->assertEquals('Second 2020', $settings->getLastByYearAndKey(2021, 2)->getValue());
    }
}