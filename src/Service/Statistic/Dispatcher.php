<?php

namespace app\Service\Statistic;

use app\Entity\ParticipantInterface;
use app\Entity\Skill;

class Dispatcher implements StatisticDispatcherInterface
{

    /**
     * @var ParticipantInterface
     */
    private $winner;

    private $haveWinnerBeforeMaximumRounds = false;

    private $round = 1;

    private $statisticReport = [];

    public function setRound(int $round): void
    {
        $this->round = $round;
    }

    public function getRound():int
    {
        return $this->round;
    }

    public function whatHappened(ParticipantInterface $attacker, ParticipantInterface $defender): void
    {
        $this->addInfoToStatistic(sprintf("%s hit %s.", $attacker->getName(), $defender->getName()));
    }

    public function damageDone(ParticipantInterface $attacker, float $damage): void
    {
        $this->addInfoToStatistic(sprintf("%s has done %s damage.", $attacker->getName(), $damage));
    }

    public function defendersHealthLeft(ParticipantInterface $defender): void
    {
        $this->addInfoToStatistic(sprintf("%s's health left %s", $defender->getName(), $defender->getHealth()));
    }

    public function skillWasUsed(ParticipantInterface $participant, Skill $skill): void
    {
        $this->addInfoToStatistic(sprintf("%s used %s skill.", $participant->getName(), $skill->getTitle()));
    }

    public function defenderIsLucky(ParticipantInterface $defender, ParticipantInterface $attacker) {
        $this->addInfoToStatistic(sprintf("Defender %s is lucky, attacker %s lose his hit", $defender->getName(), $attacker->getName()));
    }

    public function setWinner(?ParticipantInterface $participant): void
    {
        if ($participant instanceof ParticipantInterface) {
            $this->winner = $participant;
        }
    }

    public function haveWinnerBeforeMaximumRounds(bool $value): void
    {
        $this->haveWinnerBeforeMaximumRounds = $value;
    }

    public function getStatistic(): iterable
    {
        $this->statisticReport['winner'] = sprintf("Winner: %s", $this->getWinnerName());

        if ($this->haveWinnerBeforeMaximumRounds) {
            $this->statisticReport['haveWinnerBeforeMaximumRounds'] = 'We have a winner before the maximum number of rounds is reached';
        }

        return $this->statisticReport;
    }

    public function getStatisticByKey($key)
    {
        $statistic = $this->getStatistic();
        return $statistic[$key] ?? null;
    }

    private function addInfoToStatistic(string $info): void
    {
        $this->statisticReport[] = sprintf("Round %s: %s", $this->round, $info);
    }

    private function getWinnerName():string
    {
        return ($this->winner instanceof ParticipantInterface) ? $this->winner->getName() : 'Draw';
    }
}