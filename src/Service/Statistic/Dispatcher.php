<?php

namespace app\Service\Statistic;

use app\Entity\ParticipantInterface;
use app\Entity\Skill;

class Dispatcher implements StatisticDispatcherInterface
{
    private $winnerName;

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

    public function setWinnerName(string $winnerName): void
    {
        $this->winnerName = $winnerName;
        $this->statisticReport['winnerName'] = sprintf("Winner: %s", $this->winnerName);
    }

    public function haveWinnerBeforeMaximumRounds(bool $value): void
    {
        $this->haveWinnerBeforeMaximumRounds = $value;
        if($value) {
            $this->statisticReport['haveWinnerBeforeMaximumRounds'] = 'We have a winner before the maximum number of rounds is reached';
        } else {
            unset($this->statisticReport['haveWinnerBeforeMaximumRounds']);
        }
    }

    public function getStatistic(): iterable
    {
        return $this->statisticReport;
    }

    public function getStatisticByKey($key)
    {
        return $this->statisticReport[$key] ?? null;
    }

    private function addInfoToStatistic(string $info): void
    {
        $this->statisticReport[] = sprintf("Round %s: %s", $this->round, $info);
    }
}