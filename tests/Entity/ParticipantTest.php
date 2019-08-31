<?php

use app\Entity\Hero\Hero;
use app\Entity\Skill;

class ParticipantTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \app\Entity\ParticipantInterface;
     */
    private $participant;

    public function setUp():void
    {
        $participant = new Hero();

        $participant->setName('Test name');
        $participant->setHealth(10);
        $participant->setDefence(20);
        $participant->setLuck(30);
        $participant->setSpeed(40);
        $participant->setStrength(50);

        $this->participant = $participant;
    }

    public function test_participant()
    {
        $this->assertEquals('Test name', $this->participant->getName());
        $this->assertEquals(10, $this->participant->getHealth());
        $this->assertEquals(20, $this->participant->getDefence());
        $this->assertEquals(30, $this->participant->getLuck());
        $this->assertEquals(40, $this->participant->getSpeed());
        $this->assertEquals(50, $this->participant->getStrength());

        $this->participant->setHealth(0);
        $this->assertTrue($this->participant->died());
    }

    public function test_skill()
    {
        $firstSkill = $this->getFirstSkill();

        $this->assertEquals('First skill', $firstSkill->getTitle());
        $this->assertEquals(20, $firstSkill->getChance());
        $this->assertEquals('first_skill', $firstSkill->getCodeName());
    }

    public function test_participantSkills()
    {
        $this->participant->addSkill($this->getFirstSkill());
        $this->participant->addSkill($this->getSecondSkill());

        $this->assertEquals(2, count($this->participant->getSkills()));
        $this->assertEquals('first_skill', $this->participant->getSkill('first_skill')->getCodeName());

        $this->participant->removeSkill($this->getSecondSkill());

        $this->assertEquals(1, count($this->participant->getSkills()));
    }

    public function test_skillDuplicate()
    {
        $this->expectException(LogicException::class);
        $this->participant->getSkill('first_skill');
        $this->participant->removeSkill($this->getFirstSkill());

        $this->participant->addSkill($this->getFirstSkill());
        $this->expectException(LogicException::class);
        $this->participant->addSkill($this->getFirstSkill());
    }

    private function getFirstSkill():Skill
    {
        return new Skill('First skill', 20, 'first_skill');
    }

    private function getSecondSkill():Skill
    {
        return new Skill('Second skill', 35, 'second_skill');
    }
}