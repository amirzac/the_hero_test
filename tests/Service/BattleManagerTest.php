<?php

use app\Builder\Director;
use app\Builder\OrderusBuilder;
use app\Builder\WildBeastBuilder;
use app\Entity\AntiHero\AntiHero;
use app\Entity\Hero\Hero;
use app\Service\BattleManager;
use app\Service\Chance\Manager as ChanceManger;
use app\Service\Compare\Manager as CompareResultManager;
use app\Service\Statistic\Dispatcher as StatisticDispatcher;
use app\Entity\Hero\HeroInterface;
use app\Entity\AntiHero\AntiHeroInterface;


class BattleManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var BattleManager
     */
    private $battleManager;

    /**
     * @var Hero
     */
    private $hero;

    /**
     * @var AntiHero
     */
    private $antiHero;

    public function setUp(): void
    {
        $faker = Faker\Factory::create();
        $director = new Director();

        $this->hero = $director->build(new OrderusBuilder(new Hero()));
        $this->antiHero = $director->build(new WildBeastBuilder(new AntiHero()));

        $this->battleManager = new BattleManager(
            new CompareResultManager(),
            new ChanceManger($faker),
            new StatisticDispatcher(),
            $this->hero,
            $this->antiHero
        );
    }

    public function test_setRoles()
    {
        $this->hero->setSpeed(100);
        $this->antiHero->setSpeed(2);

        $this->battleManager->setRoles();

        $this->assertTrue($this->battleManager->getAttacker() instanceof HeroInterface);
        $this->assertTrue($this->battleManager->getDefender() instanceof AntiHeroInterface);

        $this->hero->setSpeed(50);
        $this->antiHero->setSpeed(50);
        $this->hero->setLuck(20);
        $this->antiHero->setLuck(70);
        $this->battleManager->setRoles();

        $this->assertTrue($this->battleManager->getAttacker() instanceof AntiHeroInterface);
        $this->assertTrue($this->battleManager->getDefender() instanceof HeroInterface);

        $this->battleManager->switchRoles();
        $this->assertTrue($this->battleManager->getAttacker() instanceof HeroInterface);
        $this->assertTrue($this->battleManager->getDefender() instanceof AntiHeroInterface);
    }

    public function test_battle()
    {
        $this->hero->setSpeed(100);
        $this->antiHero->setSpeed(2);
        $this->battleManager->setRoles();

        $this->assertTrue($this->battleManager->canContinueBattle());

        //remove skills and lucky
        $this->battleManager->getAttacker()->getSkill('magic_shield')->setChance(0);
        $this->battleManager->getAttacker()->getSkill('rapid_strike')->setChance(0);
        $this->battleManager->getDefender()->setLuck(0);
        $this->battleManager->getAttacker()->setLuck(0);

        //test damage
        $this->battleManager->getAttacker()->setStrength(80);
        $this->battleManager->getDefender()->setDefence(20);
        $this->assertEquals(60, $this->battleManager->calculateDamage());

        //test hit
        $this->battleManager->getDefender()->setHealth(60);
        $this->battleManager->hitDefender($this->battleManager->calculateDamage());
        $this->assertTrue($this->battleManager->getDefender()->died());

        $this->assertTrue($this->battleManager->getWinner() instanceof HeroInterface);

        $this->assertFalse($this->battleManager->canContinueBattle());
    }

    public function test_skillsAndLucky()
    {
        $this->hero->setSpeed(100);
        $this->antiHero->setSpeed(2);
        $this->battleManager->setRoles();

        $this->battleManager->getAttacker()->getSkill('rapid_strike')->setChance(100);
        $this->battleManager->getDefender()->setLuck(100);

        $this->assertFalse($this->battleManager->defenderNotLucky());
        $this->assertTrue($this->battleManager->attackerHasRapidStrike());

        $this->battleManager->getAttacker()->getSkill('rapid_strike')->setChance(0);
        $this->battleManager->getDefender()->setLuck(0);

        $this->assertTrue($this->battleManager->defenderNotLucky());
        $this->assertFalse($this->battleManager->attackerHasRapidStrike());

        //switch roles
        $this->battleManager->switchRoles();

        $this->battleManager->getDefender()->getSkill('magic_shield')->setChance(100);
        $this->assertTrue($this->battleManager->defenderHasMagicShield());

        $this->battleManager->getDefender()->getSkill('magic_shield')->setChance(0);
        $this->assertFalse($this->battleManager->defenderHasMagicShield());
    }

    public function test_healthFloatValue()
    {
        $this->hero->setSpeed(2);
        $this->antiHero->setSpeed(22);
        $this->battleManager->setRoles();

        $this->battleManager->getDefender()->getSkill('magic_shield')->setChance(100);

        $this->battleManager->getAttacker()->setStrength(25);
        $this->battleManager->getDefender()->setDefence(20);
        $this->battleManager->getDefender()->setHealth(5);

        $this->assertEquals(2.5, $this->battleManager->calculateDamage());

        $this->battleManager->hitDefender($this->battleManager->calculateDamage());

        $this->assertEquals(2.5, $this->battleManager->getDefender()->getHealth());
    }

    public function test_WinnerNotFound()
    {
        $this->hero->setHealth(10);
        $this->antiHero->setHealth(10);

        $this->battleManager->setRoles();
        $this->expectException(LogicException::class);
        $this->battleManager->getWinner();
    }
}