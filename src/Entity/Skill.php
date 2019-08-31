<?php

namespace app\Entity;

class Skill
{
    private $title;

    private $chance;

    private $codeName;

    public function __construct(string $title, int $chance, string $codeName)
    {
        $this->title = $title;
        $this->codeName = $codeName;
        $this->chance = $chance;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getCodeName(): string
    {
        return $this->codeName;
    }

    public function setCodeName(string $codeName): void
    {
        $this->codeName = $codeName;
    }

    public function getChance(): int
    {
        return $this->chance;
    }

    public function setChance(int $chance): void
    {
        $this->chance = $chance;
    }
}