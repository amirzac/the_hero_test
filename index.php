<?php

require __DIR__ . '/vendor/autoload.php';

use app\Builder\Director;
use app\Builder\OrderusBuilder;
use app\Builder\WildBeastBuilder;
use app\Entity\Hero\Hero;
use app\Entity\AntiHero\AntiHero;
use app\Service\BattleManager;
use app\Service\Chance\Manager as ChanceManger;
use app\Service\Statistic\Dispatcher as StatisticDispatcher;
use app\Service\Compare\Manager as CompareResultManager;

$director = new Director();
$faker = Faker\Factory::create();

$orderus = $director->build(new OrderusBuilder(new Hero()));
$wildBeast = $director->build(new WildBeastBuilder(new AntiHero()));

$battleManager = new BattleManager(
    new CompareResultManager(),
    new ChanceManger($faker),
    new StatisticDispatcher(),
    $orderus,
    $wildBeast
);

$statistic = $battleManager->battle();

$statistic->showStatistic();