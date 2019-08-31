<?php

namespace app\Entity;

abstract class Participant implements ParticipantInterface
{
    protected $name;

    protected $health;

    protected $strength;

    protected $defence;

    protected $speed;

    protected $luck;

    protected $skills = [];

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getHealth(): float
    {
        return $this->health;
    }

    public function setHealth(float $health): void
    {
        $this->health = $health;
    }

    public function getStrength(): int
    {
        return $this->strength;
    }

    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }

    public function getDefence(): int
    {
        return $this->defence;
    }

    public function setDefence(int $defence): void
    {
        $this->defence = $defence;
    }

    public function getSpeed(): int
    {
        return $this->speed;
    }

    public function setSpeed(int $speed): void
    {
        $this->speed = $speed;
    }

    public function getLuck(): int
    {
        return $this->luck;
    }

    public function setLuck(int $luck): void
    {
        $this->luck = $luck;
    }

    public function addSkill(Skill $skill): void
    {
        if (isset($this->skills[$skill->getCodeName()])) {
            throw new \LogicException('Skill already exist');
        }

        $this->skills[$skill->getCodeName()] = $skill;
    }

    public function removeSkill(Skill $skill): void
    {
        if (!isset($this->skills[$skill->getCodeName()])) {
            throw new \LogicException('Skill doesn\'t exist');
        }

        array_pop($this->skills);
    }

    public function getSkill(string $codeName): Skill
    {
        if (!isset($this->skills[$codeName])) {
            throw new \LogicException('Skill doesn\'t exist');
        }

        return $this->skills[$codeName];
    }

    public function getSkills(): iterable
    {
        return $this->skills;
    }

    public function died():bool
    {
        return $this->health <= 0;
    }
}
