<?php

namespace app\Service\Chance;

interface ChanceManagerInterface
{
    public function hadChance(int $chanceOfGettingTrue): bool;
}