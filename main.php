<?php

require 'vendor/autoload.php';

use app\Game;
use app\HandOption;

$game = new Game();
$game->setPlayer1('player_1');
$game->setPlayer2('player_2');
$game->playRounds(4);

echo "GAME 1\n";
$game->play();
$game->winner();

echo "\n\nGAME 2\n";
$optionFire = 'fire';
$optionWater = 'water';
$optionTree = 'tree';
$game->addHandOption(new HandOption(
    $optionFire,
    [HandOption::OPTION_SCISSORS, HandOption::OPTION_PAPER, $optionTree],
    [HandOption::OPTION_STONE, $optionWater]
));
$game->addHandOption(new HandOption(
    $optionWater,
    [HandOption::OPTION_SCISSORS, HandOption::OPTION_STONE, $optionFire],
    [HandOption::OPTION_PAPER, $optionTree]
));
$game->addHandOption(new HandOption(
    $optionTree,
    [HandOption::OPTION_PAPER, $optionWater],
    [HandOption::OPTION_SCISSORS, HandOption::OPTION_STONE, $optionFire]
));

$game->play();
$game->winner();
