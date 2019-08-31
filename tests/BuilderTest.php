<?php

use app\Builder\Director;
use app\Builder\OrderusBuilder;
use app\Builder\WildBeastBuilder;
use app\Entity\Hero\Hero;
use app\Entity\AntiHero\AntiHero;
use app\Entity\Hero\HeroInterface;
use app\Entity\AntiHero\AntiHeroInterface;

class BuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Director
     */
    private $director;

    public function setUp():void
    {
        $this->director = new Director();
    }

    public function test_heroBuilder()
    {
        /* @var HeroInterface $hero */
        $hero = $this->director->build(new OrderusBuilder(new Hero()));

        $this->checkIfBetween($hero->getHealth(), 70, 100);
        $this->checkIfBetween($hero->getStrength(), 70, 80);
        $this->checkIfBetween($hero->getDefence(), 45, 55);
        $this->checkIfBetween($hero->getSpeed(), 40, 50);
        $this->checkIfBetween($hero->getLuck(), 10, 30);

        $this->assertEquals(2, count($hero->getSkills()));
        $this->assertEquals(10, $hero->getSkill('rapid_strike')->getChance());
        $this->assertEquals(20, $hero->getSkill('magic_shield')->getChance());
    }

    public function test_antiHeroBuilder()
    {
        /* @var AntiHeroInterface $antiHero */
        $antiHero = $this->director->build(new WildBeastBuilder(new AntiHero()));

        $this->checkIfBetween($antiHero->getHealth(), 60, 90);
        $this->checkIfBetween($antiHero->getStrength(), 60, 90);
        $this->checkIfBetween($antiHero->getDefence(), 40, 60);
        $this->checkIfBetween($antiHero->getSpeed(), 40, 60);
        $this->checkIfBetween($antiHero->getLuck(), 25, 40);

        $this->assertEquals(0, count($antiHero->getSkills()));
    }

    private function checkIfBetween($checkAttribute, $lessAttribute, $biggerAttribute)
    {
        $this->assertThat(
            $checkAttribute,
            $this->logicalAnd(
                $this->greaterThanOrEqual($lessAttribute),
                $this->lessThanOrEqual($biggerAttribute)
            )
        );
    }
}