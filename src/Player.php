<?php

namespace app;

class Player {

    /**
     * @var string
     */
    private $name;
    /**
     * @var app\HandOption
     */
    private $hand;
    /**
     * @var app\HandManager
     */
    private $handManager;

    public function __construct($name, HandManager $handManager) {
        $this->name = $name;
        $this->handManager = $handManager;
    }

    /**
     * Picks a new pair of hands for this and the enemy player and checks if this player wins.
     * @param \app\Player $enemy
     * @return integer 1 if this player has won, -1 if the enemy has won or 0 if the match is draw.
     */
    public function playAgainst(Player $enemy) {
        $thisHand = $this->pickHand();
        $enemyHand = $enemy->pickHand();

        return $thisHand->compare($enemyHand);
    }

    /**
     * @return app\HandOption
     */
    public function pickHand() {
        $this->hand = $this->handManager->pick();

        return $this->hand;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

}
