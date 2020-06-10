<?php
namespace BretRZaun\Settings\Test;

use PHPUnit\Framework\TestCase;
use BretRZaun\Settings\Value\SimpleValue;
use BretRZaun\Settings\Settings;
use BretRZaun\Settings\Renderer\MoneyRenderer;

class SettingsTests extends TestCase
{
    public function testAdd(): void
    {
        $first = new SimpleValue(1, 'First');
        $second = new SimpleValue(2, 'Second');

        $settings = new Settings([$first]);
        $settings->add($second);

        $this->assertEquals(
            [1 => $first, 2 => $second],
            $settings->toArray()
        );
    }

    public function testRemove(): void
    {
        $first = new SimpleValue(1, 'First');
        $second = new SimpleValue(2, 'Second');

        $settings = new Settings;
        $settings->add($first);
        $settings->add($second);
        $this->assertCount(2, $settings);

        $settings->removeElement($first);
        $this->assertCount(1, $settings);
        $this->assertEquals(
            [2 => $second],
            $settings->toArray()
        );
    }

    public function testMoneyRenderer(): void
    {
        $first = new SimpleValue(1, 5000.5);
        $second = new SimpleValue(2, 20.75);

        $renderer = new MoneyRenderer([
            'dec_point' => ',',
            'thousands_sep' => '.',
            'currency' => '€'
        ]);
        $settings = new Settings([$first, $second], $renderer);

        $this->assertEquals(5000.5, $settings[1]->getValue());
        $this->assertEquals('5.000,50 €', $settings[1]->getRenderedValue());
    }
}