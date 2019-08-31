<?php

namespace app\Builder;

use app\Entity\AntiHero\AntiHeroInterface;
use app\Entity\Hero\HeroInterface;

/**
 * Class Director
 * @return HeroInterface|AntiHeroInterface
 * @package app\Builder
 */
class Director
{
    public function build(BuilderInterface $builder)
    {
        $builder->setName();
        $builder->setLuck();
        $builder->setSpeed();
        $builder->setDefence();
        $builder->setStrength();
        $builder->setHealth();
        $builder->addSkills();

        return $builder->getParticipant();
    }
}