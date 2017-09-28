<?php

namespace app;

use app\exceptions\InvalidHandOptionException;

class HandOption {

    const OPTION_STONE = 'stone';
    const OPTION_PAPER = 'paper';
    const OPTION_SCISSORS = 'scissors';

    const COMPARISON_WIN = 1;
    const COMPARISON_LOSE = -1;
    const COMPARISON_DRAW = 0;

    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $beats;
    /**
     * @var array
     */
    private $beatenBy;

    public function __construct($name, $beats = [], $beatenBy = []) {
        if (empty($beats) && empty($beatenBy)) {
            throw new InvalidHandOptionException('Hand option must at least beat or be beaten.');
        } else if (empty($beats)) {
            $beats = [];
        } else if (empty($beatenBy)) {
            $beatenBy = [];
        }
        if (!is_string($name) || (!is_string($beats) && !is_array($beats)) ||
            (!is_string($beatenBy) && !is_array($beatenBy)) ||
            $name === $beats || $name === $beatenBy || $beats === $beatenBy
        ) {
            throw new InvalidHandOptionException();
        }

        $this->name = $name;
        $this->beats = is_array($beats) ? $beats : [$beats];
        $this->beatenBy = is_array($beatenBy) ? $beatenBy : [$beatenBy];
    }

    /**
     * Compares this option to the target one.
     * @param \app\HandOption $option
     * @return integer 1 if this has won, -1 if the enemy has won or 0 if the match is draw.
     */
    public function compare(HandOption $option) {
        if ($this->name === $option->name) {
            return self::COMPARISON_DRAW;
        } else if ($this->beats($option)) {
            return self::COMPARISON_WIN;
        } else if ($option->beats($this)) {
            return self::COMPARISON_LOSE;
        } else {
            return self::COMPARISON_DRAW;
        }
    }

    /**
     * Checks if this hand option beats the target option.
     * @param \app\HandOption $otherOption
     * @return boolean TRUE if this hand option beats the target option, FALSE otherwise.
     */
    public function beats(HandOption $otherOption) {
        return in_array($otherOption->name, $this->beats) ||
            in_array($this->name, $otherOption->beatenBy);
    }

    /**
     * Checks if this hand option is the same as the trget one.
     * @param \app\HandOption $otherOption
     * @return boolean
     */
    public function isIdentical(HandOption $otherOption) {
        return $this->name === $otherOption->name &&
            $this->beats === $otherOption->beats &&
            $this->beatenBy === $otherOption->beatenBy;
    }

    /**
     * Gets the name of this hand option.
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}
