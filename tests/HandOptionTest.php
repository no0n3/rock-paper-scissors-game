<?php

use PHPUnit\Framework\TestCase;
use app\HandOption;

class HandOptionTest extends TestCase {
    private $optionStone;
    private $optionScissors;
    private $optionPaper;

    public function __construct($name = null, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $this->optionStone = new HandOption(
            HandOption::OPTION_STONE, HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER
        );
        $this->optionScissors = new HandOption(
            HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER, HandOption::OPTION_STONE
        );
        $this->optionPaper = new HandOption(
            HandOption::OPTION_PAPER, HandOption::OPTION_STONE, HandOption::OPTION_SCISSORS
        );
    }

    public function testStandartWinOptionBeatComparison() {
        $this->assertTrue($this->optionStone->beats($this->optionScissors));
        $this->assertTrue($this->optionScissors->beats($this->optionPaper));
        $this->assertTrue($this->optionPaper->beats($this->optionStone));
    }

    public function testStandartNotWinOptionBeatComparison() {
        $this->assertFalse($this->optionScissors->beats($this->optionStone));
        $this->assertFalse($this->optionScissors->beats($this->optionScissors));
        $this->assertFalse($this->optionStone->beats($this->optionPaper));
        $this->assertFalse($this->optionStone->beats($this->optionStone));
        $this->assertFalse($this->optionPaper->beats($this->optionScissors));
        $this->assertFalse($this->optionPaper->beats($this->optionPaper));
    }

    public function testStandartWinOptionComparison() {
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $this->optionStone->compare($this->optionScissors)
        );
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $this->optionScissors->compare($this->optionPaper)
        );
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $this->optionPaper->compare($this->optionStone)
        );
    }

    public function testStandartDrawOptionComparison() {
        $this->assertEquals(
            HandOption::COMPARISON_DRAW,
            $this->optionStone->compare($this->optionStone)
        );
        $this->assertEquals(
            HandOption::COMPARISON_DRAW,
            $this->optionScissors->compare($this->optionScissors)
        );
        $this->assertEquals(
            HandOption::COMPARISON_DRAW,
            $this->optionPaper->compare($this->optionPaper)
        );
    }

    public function testStandartLoseOptionComparison() {
        $this->assertEquals(
            HandOption::COMPARISON_LOSE,
            $this->optionStone->compare($this->optionPaper)
        );
        $this->assertEquals(
            HandOption::COMPARISON_LOSE,
            $this->optionScissors->compare($this->optionStone)
        );
        $this->assertEquals(
            HandOption::COMPARISON_LOSE,
            $this->optionPaper->compare($this->optionScissors)
        );
    }

    public function testCustomOptionComparison() {
        $optionFire = $this->getFireOption();
        $optionWater = $this->getWaterOption();

        $this->assertEquals(
            HandOption::COMPARISON_LOSE,
            $optionFire->compare($this->optionStone)
        );
        $this->assertEquals(
            HandOption::COMPARISON_LOSE,
            $optionFire->compare($optionWater)
        );
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $optionFire->compare($this->optionScissors)
        );
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $optionFire->compare($this->optionPaper)
        );
        $this->assertEquals(
            HandOption::COMPARISON_WIN,
            $optionWater->compare($optionFire)
        );
        $this->assertEquals(
            HandOption::COMPARISON_DRAW,
            $optionWater->compare($optionWater)
        );
        $this->assertEquals(
            HandOption::COMPARISON_DRAW,
            $optionFire->compare($optionFire)
        );
    }

    public function testCustomOptionBeats() {
        $optionFire = $this->getFireOption();
        $optionWater = $this->getWaterOption();

        $this->assertTrue($optionFire->beats($this->optionScissors));
        $this->assertTrue($optionFire->beats($this->optionPaper));
        $this->assertTrue($optionWater->beats($optionFire));
        $this->assertTrue($optionWater->beats($this->optionScissors));
        $this->assertTrue($optionWater->beats($this->optionStone));
        $this->assertTrue($this->optionPaper->beats($optionWater));
        $this->assertTrue($this->optionStone->beats($optionFire));
        $this->assertFalse($optionWater->beats($optionWater));
        $this->assertFalse($optionFire->beats($optionFire));
        $this->assertFalse($optionFire->beats($optionWater));
    }

    /**
    * @expectedException app\exceptions\InvalidHandOptionException
    */
    public function testInvalidOption() {
        $option = new HandOption('test', [], []);
    }

    public function testUnbeattableOption() {
        $optionLuck = new HandOption('luck', [
            'fire', 'water', HandOption::OPTION_PAPER,
            HandOption::OPTION_SCISSORS, HandOption::OPTION_STONE,
        ], []);
        $optionFire = $this->getFireOption();
        $optionWater = $this->getWaterOption();

        $this->assertTrue($optionLuck->beats($optionFire));
        $this->assertTrue($optionLuck->beats($optionWater));
        $this->assertTrue($optionLuck->beats($this->optionStone));
        $this->assertTrue($optionLuck->beats($this->optionPaper));
        $this->assertTrue($optionLuck->beats($this->optionScissors));
        $this->assertFalse($optionFire->beats($optionLuck));
        $this->assertFalse($optionWater->beats($optionLuck));
        $this->assertFalse($this->optionStone->beats($optionLuck));
        $this->assertFalse($this->optionPaper->beats($optionLuck));
        $this->assertFalse($this->optionScissors->beats($optionLuck));
    }

    public function testOptionWithNoWinChance() {
        $optionUnlucky = new HandOption('unlucky',
            [], [
            'fire', 'water', HandOption::OPTION_PAPER,
            HandOption::OPTION_SCISSORS, HandOption::OPTION_STONE,
        ]);
        $optionFire = $this->getFireOption();
        $optionWater = $this->getWaterOption();

        $this->assertFalse($optionUnlucky->beats($optionFire));
        $this->assertFalse($optionUnlucky->beats($optionWater));
        $this->assertFalse($optionUnlucky->beats($this->optionStone));
        $this->assertFalse($optionUnlucky->beats($this->optionPaper));
        $this->assertFalse($optionUnlucky->beats($this->optionScissors));
        $this->assertTrue($optionFire->beats($optionUnlucky));
        $this->assertTrue($optionWater->beats($optionUnlucky));
        $this->assertTrue($this->optionStone->beats($optionUnlucky));
        $this->assertTrue($this->optionPaper->beats($optionUnlucky));
        $this->assertTrue($this->optionScissors->beats($optionUnlucky));
    }

    private function getFireOption() {
        return new HandOption(
            'fire',
            [HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER],
            [HandOption::OPTION_STONE, 'water']
        );
    }

    private function getWaterOption() {
        return new HandOption(
            'water',
            [HandOption::OPTION_SCISSORS, HandOption::OPTION_STONE, 'fire'],
            [HandOption::OPTION_PAPER]
        );
    }
}
