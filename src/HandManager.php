<?php

namespace app;

class HandManager {

    /**
     * @var array
     */
    private $handOptions;

    public function __construct() {
        $this->handOptions = [
            new HandOption(
                HandOption::OPTION_STONE, HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER
            ),
            new HandOption(
                HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER, HandOption::OPTION_STONE
            ),
            new HandOption(
                HandOption::OPTION_PAPER, HandOption::OPTION_STONE, HandOption::OPTION_SCISSORS
            )
        ];
    }

    /**
     * Adds a new hand option.
     * @param \app\HandOption $newOption
     */
    public function addHandOption(HandOption $newOption) {
        if ($this->optionExists($newOption)) {
            return;
        }

        $this->handOptions[] = $newOption;
    }

    /**
     * Checks if the current option is registered.
     * @param \app\HandOption $targetOption
     * @return boolean TRUE if the option was registered, FALSE otherwise.
     */
    private function optionExists(HandOption $targetOption) {
        foreach ($this->handOptions as $option) {
            if ($option->isIdentical($targetOption)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets an random hand opton from the currently registered ones.
     * @return app\HandOption The randomly picked option.
     */
    public function pick() {
        return $this->handOptions[rand(0, count($this->handOptions) - 1)];
    }

}
