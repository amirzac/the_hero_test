<?php

namespace app\Builder;

interface BuilderInterface
{
    public function setName(): void;

    public function setHealth(): void;

    public function setStrength(): void;

    public function setDefence(): void;

    public function setSpeed(): void;

    public function setLuck(): void;

    public function addSkills(): void;

    public function getParticipant();
}