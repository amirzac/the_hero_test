<?php

namespace app\Builder;

use app\Entity\Hero\HeroInterface;
use app\Entity\Skill;

class OrderusBuilder implements BuilderInterface
{
    private $participant;

    public function __construct(HeroInterface $participant)
    {
        $this->participant = $participant;
    }

    public function setName(): void
    {
        $this->participant->setName('Orderus');
    }

    public function setHealth(): void
    {
        $this->participant->setHealth(rand(70, 100));
    }

    public function setStrength(): void
    {
        $this->participant->setStrength(rand(70, 80));
    }

    public function setDefence(): void
    {
        $this->participant->setDefence(rand(45, 55));
    }

    public function setSpeed(): void
    {
        $this->participant->setSpeed(rand(40, 50));
    }

    public function setLuck(): void
    {
        $this->participant->setLuck(rand(10, 30));
    }

    public function addSkills(): void
    {
        $this->participant->addSkill(new Skill('Rapid strike', 10, 'rapid_strike'));

        $this->participant->addSkill(new Skill('Magic shield', 20, 'magic_shield'));
    }

    public function getParticipant(): HeroInterface
    {
        return $this->participant;
    }
}