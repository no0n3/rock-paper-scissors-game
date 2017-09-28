<?php

namespace app;

use Exception;

class Game {

    /**
     * @var integer
     */
    private $rounds;
    /**
     * @var app\Player
     */
    private $player1;
    /**
     * @var app\Player
     */
    private $player2;
    /**
     * @var app\HandManager
     */
    private $handManager;
    /**
     * @var array
     */
    private $roundWins;

    public function __construct() {
        $this->playRounds(1);
        $this->roundWins = [];
        $this->handManager = new HandManager();
    }

    /**
     * Sets player one with the supplied name.
     * @param string $name
     */
    public function setPlayer1($name) {
        if (!empty($this->player2) && $this->player2->getName() === $name) {
            throw new Exception('A player with the same name is already registered.');
        }

        $this->player1 = new Player($name, $this->handManager);
    }

    /**
     * Sets player two with the supplied name.
     * @param string $name
     */
    public function setPlayer2($name) {
        if (!empty($this->player1) && $this->player1->getName() === $name) {
            throw new Exception('A player with the same name is already registered.');
        }

        $this->player2 = new Player($name, $this->handManager);
    }

    /**
     * Sets the rounds to be played.
     * @param integer $rounds
     */
    public function playRounds($rounds) {
        if (!is_numeric($rounds) || 0 >= $rounds) {
            return;
        }

        $this->rounds = $rounds;
    }

    /**
     * Stars the game, playing all the rounds.
     */
    public function play() {
        $this->roundWins = [];

        for ($round = 0; $round < $this->rounds; $round++) {
            $winner = $this->playRound();
            $curRound = $round + 1;
            echo "round {$curRound} winner is {$winner->getName()}\n";
            $this->roundWins[$round] = $winner;
        }
    }

    /**
     * Plays a rounds until one of the players has won.
     * @return app\Player The winner of this round.
     */
    private function playRound() {
        while (true) {
            switch ($this->player1->playAgainst($this->player2)) {
                case HandOption::COMPARISON_WIN:
                    return $this->player1;
                case HandOption::COMPARISON_LOSE:
                    return $this->player2;
            }
        }
    }

    /**
     * Prints the winner of the previously played game.
     */
    public function winner() {
        if (empty($this->roundWins)) {
            return;
        }
        if (empty($this->player1)) {
            throw new Exception('Player1 is invalid.');
        }
        if (empty($this->player2)) {
            throw new Exception('Player2 is invalid.');
        }

        $roundsWon = [];
        foreach ($this->roundWins as $roundWinner) {
            if (empty($roundWinner)) {
                continue;
            }

            $roundsWon[$roundWinner->getName()] = (!isset($roundsWon[$roundWinner->getName()]) || !is_numeric($roundsWon[$roundWinner->getName()])) ?
                1 :
                ($roundsWon[$roundWinner->getName()] + 1);
        }

        asort($roundsWon, SORT_NUMERIC);
        if (1 < array_keys($roundsWon) &&
            $roundsWon[array_keys($roundsWon)[count($roundsWon) - 1]] === $roundsWon[array_keys($roundsWon)[count($roundsWon) - 2]]
        ) {
            echo sprintf("The game is draw\n");

            return;
        }

        $firstPlayer = array_keys($roundsWon)[count($roundsWon) - 1];
        echo sprintf("%s has won the game\n", $firstPlayer);
    }

    /**
     * Adds a new hand option.
     * @param \app\HandOPtion $option
     */
    public function addHandOption(HandOPtion $option) {
        $this->handManager->addHandOption($option);
    }

}
