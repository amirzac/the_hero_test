<?php

use app\Service\Chance\Manager as ChanceManager;

class ChanceManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \app\Service\Chance\ChanceManagerInterface
     */
    private $chanceManager;

    public function setUp():void
    {
        $faker = Faker\Factory::create();

        $this->chanceManager = new ChanceManager($faker);
    }

    public function test_hasChance()
    {
        $this->assertEquals(true, $this->chanceManager->hadChance(100));
    }

    public function test_notChance()
    {
        $this->assertEquals(false, $this->chanceManager->hadChance(0));
    }

    public function test_random()
    {
        $this->assertIsBool($this->chanceManager->hadChance(50));
    }

    public function test_wrong_attribute_exception()
    {
        $this->expectException(TypeError::class);

        $this->chanceManager->hadChance('node valid attribute');
    }
}