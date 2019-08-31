<?php

use app\Service\Compare\Manager as CompareManager;

class CompareManagerTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \app\Service\Compare\CompareResultInterface
     */
    private $compareManager;

    public function setUp():void
    {
        $this->compareManager = new CompareManager();
    }

    public function test_isMore()
    {
        $this->compareManager->setComparedValue(10 <=> 1);
        $this->assertTrue($this->compareManager->isMore());
    }

    public function test_isLess()
    {
        $this->compareManager->setComparedValue(1 <=> 99);
        $this->assertTrue($this->compareManager->isLess());
    }

    public function test_isEqual()
    {
        $this->compareManager->setComparedValue(99 <=> 99);
        $this->assertTrue($this->compareManager->isEqual());
    }

    public function test_WrongTypeException()
    {
        $this->expectException(TypeError::class);
        $this->compareManager->setComparedValue('wrong type');
    }

    public function test_WrongAttributeException()
    {
        $this->expectException(LogicException::class);
        $this->compareManager->setComparedValue(3);
    }

    public function test_nullableComparedValue()
    {
        $this->expectException(LogicException::class);
        $this->compareManager->isEqual();
        $this->compareManager->isMore();
        $this->compareManager->isLess();
    }
}