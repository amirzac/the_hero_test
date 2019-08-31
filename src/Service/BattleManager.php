<?php

namespace app\Service;

use app\Entity\AntiHero\AntiHeroInterface;
use app\Entity\Hero\HeroInterface;
use app\Entity\ParticipantInterface;
use app\Service\Statistic\StatisticDispatcherInterface;
use app\Service\Chance\ChanceManagerInterface;
use app\Service\Compare\CompareResultInterface;

class BattleManager
{
    private const MAX_TURNS = 20;

    /**
     * @var ParticipantInterface
     */
    private $attacker;

    /**
     * @var ParticipantInterface
     */
    private $defender;

    private $compareResultManager;

    private $chanceManager;

    private $statisticDispatcher;

    private $hero;

    private $antiHero;

    private $round = 1;

    public function __construct(
        CompareResultInterface $compareResultManager,
        ChanceManagerInterface $chanceManager,
        StatisticDispatcherInterface $statisticDispatcher,
        HeroInterface $hero,
        AntiHeroInterface $antiHero
    )
    {
        $this->compareResultManager = $compareResultManager;
        $this->chanceManager = $chanceManager;
        $this->hero = $hero;
        $this->antiHero = $antiHero;
        $this->statisticDispatcher = $statisticDispatcher;

        $this->statisticDispatcher->setTurn($this->round);
    }

    public function battle(): StatisticDispatcherInterface
    {
        $this->setRoles();

        while ($this->canContinueBattle()) {

            if ($this->defenderNotLucky()) {
                $this->hitDefender($this->calculateDamage());
            }

            if ($this->attackerHasRapidStrike()) {
                $this->hitDefender($this->calculateDamage());
            }

            $this->finishTurn();
            $this->switchRoles();
        }

        return $this->statisticDispatcher;
    }

    private function hitDefender(float $damage): void
    {
        $ifDefenderNotLucky = !$this->chanceManager->hadChance($this->defender->getLuck());

        if ($ifDefenderNotLucky) {
            $this->statisticDispatcher->whatHappened($this->attacker, $this->defender);
            $this->statisticDispatcher->damageDone($this->attacker, $damage);

            $this->defender->setHealth($this->defender->getHealth() - $damage);

            $this->statisticDispatcher->defendersHealthLeft($this->defender);
        }
    }

    private function setRoles(): void
    {
        //comparing speed
        $this->compareResultManager->setComparedValue($this->hero->getSpeed() <=> $this->antiHero->getSpeed());
        $this->setRolesInCondition(function () {
            //comparing luck
            $this->compareResultManager->setComparedValue($this->hero->getLuck() <=> $this->antiHero->getLuck());
            $this->setRolesInCondition(function () {
                throw new \LogicException('Unsupported occasion. Lucky is equal');
            });
        });
    }

    private function setRolesInCondition(callable $function): void
    {
        if ($this->compareResultManager->isMore()) {
            $this->attacker = $this->hero;
            $this->defender = $this->antiHero;
        } elseif ($this->compareResultManager->isLess()) {
            $this->defender = $this->hero;
            $this->attacker = $this->antiHero;
        } elseif ($this->compareResultManager->isEqual()) {
            call_user_func($function);
        }
    }

    private function switchRoles(): void
    {
        $temp = $this->defender;
        $this->defender = $this->attacker;
        $this->attacker = $temp;
    }

    private function calculateDamage(): float
    {
        $damage = ($this->attacker->getStrength() - $this->defender->getDefence());
        if ($this->defenderHasMagicShield()) {
            $damage = round($damage / 2, 1);
        }

        return $damage;
    }

    private function defenderNotLucky(): bool
    {
        return !$this->chanceManager->hadChance($this->defender->getLuck());
    }

    private function attackerHasRapidStrike(): bool
    {
        return $this->skillActive($this->attacker, 'rapid_strike');
    }

    private function defenderHasMagicShield(): bool
    {
        return $this->skillActive($this->defender, 'magic_shield');
    }

    private function canContinueBattle(): bool
    {
        $continue = true;

        if ($this->round >= self::MAX_TURNS) {
            $continue = false;
        }

        if ($this->participantDie($this->attacker) || $this->participantDie($this->defender)) {
            $continue = false;
            $this->statisticDispatcher->haveWinnerBeforeMaximumRounds(1);
        }

        if (!$continue) {
            $this->statisticDispatcher->setWinner($this->getWinner());
        }

        return $continue;
    }

    private function getWinner(): ?ParticipantInterface
    {
        $this->compareResultManager->setComparedValue($this->attacker->getHealth() <=> $this->defender->getHealth());

        if ($this->compareResultManager->isMore()) {
            return $this->attacker;
        } elseif ($this->compareResultManager->isLess()) {
            return $this->defender;
        } else {
            return null;
        }
    }

    private function participantDie(ParticipantInterface $participant): bool
    {
        return ($participant->getHealth() <= 0);
    }

    private function finishTurn(): void
    {
        $this->round++;
        $this->statisticDispatcher->setTurn($this->round);
    }

    private function skillActive(ParticipantInterface $participant, string $codeName): bool
    {
        try {
            $skill = $participant->getSkill($codeName);

            $hadChance = $this->chanceManager->hadChance($skill->getChance());

            if ($hadChance) {
                $this->statisticDispatcher->skillWasUsed($participant, $skill);
            }

            return $hadChance;
        } catch (\LogicException $exception) {
            return false;
        }
    }
}