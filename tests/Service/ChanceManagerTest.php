<?php

use app\Service\Chance\Manager;

class ChanceManagerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \app\Service\Chance\ChanceManagerInterface
     */
    private $chanceManager;

    public function setUp()
    {
        $faker = Faker\Factory::create();

        $this->chanceManager = new Manager($faker);
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
        $this->assertInternalType('boolean', $this->chanceManager->hadChance(50));
    }

    public function test_wrong_attribute_exception()
    {
        $this->setExpectedException(TypeError::class);

        $this->chanceManager->hadChance('node valid attribute');
    }
}