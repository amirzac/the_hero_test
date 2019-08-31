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

    private $turn;

    private $statisticReport = [];

    public function setTurn(int $turn): void
    {
        $this->turn = $turn;
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

    public function showStatistic(): void
    {
        $this->statisticReport = array_merge($this->statisticReport, ['Winner' => $this->winner->getName() ?? 'Draw']);

        if ($this->haveWinnerBeforeMaximumRounds) {
            $this->statisticReport = array_merge($this->statisticReport, [
                'haveWinnerBeforeMaximumRounds' => 'We have a winner before the maximum number of rounds is reached'
            ]);
        }

        print_r($this->statisticReport);
    }

    private function addInfoToStatistic(string $info): void
    {
        $this->statisticReport[$this->turn][] = $info;
    }
}