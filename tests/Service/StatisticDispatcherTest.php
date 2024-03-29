<?php

use app\Service\Statistic\Dispatcher as StatiticDispatcher;
use app\Builder\Director;
use app\Builder\OrderusBuilder;
use app\Builder\WildBeastBuilder;
use app\Entity\Hero\Hero;
use app\Entity\AntiHero\AntiHero;
use app\Entity\Skill;

class StatisticDispatcherTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \app\Entity\Hero\HeroInterface
     */
    private $hero;

    /**
     * @var \app\Entity\AntiHero\AntiHeroInterface
     */
    private $antiHero;

    /**
     * @var \app\Service\Statistic\StatisticDispatcherInterface
     */
    private $statisticDispatcher;

    public function setUp():void
    {
        $director = new Director();

        $this->hero = $director->build(new OrderusBuilder(new Hero()));
        $this->antiHero = $director->build(new WildBeastBuilder(new AntiHero()));
        $this->statisticDispatcher = new StatiticDispatcher();
    }

    public function test_round()
    {
        $this->assertEquals(1, $this->statisticDispatcher->getRound());

        $this->statisticDispatcher->setRound(3);
        $this->assertEquals(3, $this->statisticDispatcher->getRound());
    }

    public function test_whatHappened()
    {
        //what happened
        $this->statisticDispatcher->whatHappened($this->hero, $this->antiHero);
        $this->assertEquals('Round 1: Orderus hit Wild Beast.', $this->statisticDispatcher->getStatisticByKey(0));

        //damage
        $this->statisticDispatcher->damageDone($this->antiHero, 99);
        $this->assertEquals('Round 1: Wild Beast has done 99 damage.', $this->statisticDispatcher->getStatisticByKey(1));

        //health
        $this->statisticDispatcher->setRound(2);
        $this->statisticDispatcher->defendersHealthLeft($this->hero);
        $this->assertEquals(
            sprintf("Round 2: Orderus's health left %s", $this->hero->getHealth()),
            $this->statisticDispatcher->getStatisticByKey(2)
        );

        //used skills
        $this->statisticDispatcher->skillWasUsed($this->hero, new Skill('Test', 10, 'test_skill'));
        $this->assertEquals('Round 2: Orderus used Test skill.', $this->statisticDispatcher->getStatisticByKey(3));

        //winner name
        $this->statisticDispatcher->setWinnerName('Draw');
        $this->assertEquals('Winner: Draw', $this->statisticDispatcher->getStatisticByKey('winnerName'));
        $this->statisticDispatcher->setWinnerName($this->hero->getName());
        $this->assertEquals('Winner: Orderus', $this->statisticDispatcher->getStatisticByKey('winnerName'));

        //defender is lucky
        $this->statisticDispatcher->defenderIsLucky($this->hero, $this->antiHero);
        $this->assertEquals(
            'Round 2: Defender Orderus is lucky, attacker Wild Beast lose his hit',
            $this->statisticDispatcher->getStatisticByKey(4)
        );

        //max rounds
        $this->assertEmpty($this->statisticDispatcher->getStatisticByKey('haveWinnerBeforeMaximumRounds'));
        $this->statisticDispatcher->haveWinnerBeforeMaximumRounds(true);
        $this->assertEquals(
            'We have a winner before the maximum number of rounds is reached',
            $this->statisticDispatcher->getStatisticByKey('haveWinnerBeforeMaximumRounds')
        );

    }
}