<?php

namespace app\Entity;

interface ParticipantInterface
{
    public function getName(): string;

    public function setName(string $name): void;

    public function getHealth(): int;

    public function setHealth(int $health): void;

    public function getStrength(): int;

    public function setStrength(int $strength): void;

    public function getDefence(): int;

    public function setDefence(int $defence): void;

    public function getSpeed(): int;

    public function setSpeed(int $speed): void;

    public function getLuck(): int;

    public function setLuck(int $luck): void;

    public function addSkill(Skill $skill): void;

    public function removeSkill(Skill $skill): void;

    public function died():bool;

    public function getSkill(string $codeName): Skill;

    /**
     * @return Skill[]
     */
    public function getSkills(): iterable;
}