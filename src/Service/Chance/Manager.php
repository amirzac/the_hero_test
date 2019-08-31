<?php

namespace app\Service\Chance;

use Faker\Generator;

class Manager implements ChanceManagerInterface
{
    private $faker;

    public function __construct(Generator $faker)
    {
        $this->faker = $faker;
    }

    public function hadChance(int $chanceOfGettingTrue): bool
    {
        return $this->faker->boolean($chanceOfGettingTrue);
    }
}