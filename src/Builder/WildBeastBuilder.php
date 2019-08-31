<?php

namespace app\Builder;

use app\Entity\AntiHero\AntiHeroInterface;

class WildBeastBuilder implements BuilderInterface
{
    private $participant;

    public function __construct(AntiHeroInterface $participant)
    {
        $this->participant = $participant;
    }

    public function setName(): void
    {
        $this->participant->setName('Wild Beast');
    }

    public function setHealth(): void
    {
        $this->participant->setHealth(rand(60, 90));
    }

    public function setStrength(): void
    {
        $this->participant->setStrength(rand(60, 90));
    }

    public function setDefence(): void
    {
        $this->participant->setDefence(rand(40, 60));
    }

    public function setSpeed(): void
    {
        $this->participant->setSpeed(rand(40, 60));
    }

    public function setLuck(): void
    {
        $this->participant->setLuck(rand(25, 40));
    }

    public function getParticipant(): AntiHeroInterface
    {
        return $this->participant;
    }

    public function addSkills(): void
    {
        return;
    }
}