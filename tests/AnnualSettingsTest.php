<?php
namespace BretRZaun\Settings\Test;

use PHPUnit\Framework\TestCase;
use BretRZaun\Settings\Value\AnnualValue;
use BretRZaun\Settings\AnnualSettings;

/**
 * @group annual
 */
class AnnualSettingsTests extends TestCase
{
    private function getAnnualSettings(): AnnualSettings
    {
        $first2018 = new AnnualValue(1, 'First 2018', 2018);
        $second2018 = new AnnualValue(2, 'Second 2018', 2018);
        $first2020 = new AnnualValue(1, 'First 2020', 2020);
        $second2020 = new AnnualValue(2, 'Second 2020', 2020);

        $settings = new AnnualSettings([$first2018, $second2018, $first2020, $second2020]);
        return $settings;
    }

    public function testAdd(): void
    {
        $settings = $this->getAnnualSettings();
        $this->assertCount(4, $settings);
    }

    public function testGetByYearAndKey(): void
    {
        $settings = $this->getAnnualSettings();

        $this->assertEquals('First 2018', $settings->getByYearAndKey(2018, 1)->getValue());
        $this->assertEquals('Second 2020', $settings->getByYearAndKey(2020, 2)->getValue());
        $this->assertNull($settings->getByYearAndKey(2021, 1));
    }

    public function testFindByYear(): void
    {
        $settings = $this->getAnnualSettings();

        $year2018 = $settings->findByYear(2018);
        $this->assertCount(2, $year2018);
        $this->assertEquals('First 2018', $year2018[1]->getValue());
    }

    public function testGetLastByYearAndKey(): void
    {
        $settings = $this->getAnnualSettings();

        $this->assertNull($settings->getLastByYearAndKey(2017, 1));
        $this->assertEquals('First 2018', $settings->getLastByYearAndKey(2018, 1)->getValue());
        $this->assertEquals('First 2018', $settings->getLastByYearAndKey(2019, 1)->getValue());
        $this->assertEquals('Second 2020', $settings->getLastByYearAndKey(2020, 2)->getValue());
        $this->assertEquals('Second 2020', $settings->getLastByYearAndKey(2021, 2)->getValue());
    }
}