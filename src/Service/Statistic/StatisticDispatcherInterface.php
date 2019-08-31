<?php

namespace app\Service\Statistic;

use app\Entity\ParticipantInterface;
use app\Entity\Skill;

interface StatisticDispatcherInterface
{
    public function setTurn(int $turn): void;

    public function whatHappened(ParticipantInterface $attacker, ParticipantInterface $defender): void;

    public function damageDone(ParticipantInterface $attacker, float $damage): void;

    public function defendersHealthLeft(ParticipantInterface $defender): void;

    public function skillWasUsed(ParticipantInterface $participant, Skill $skill): void;

    public function setWinner(?ParticipantInterface $participant): void;

    public function haveWinnerBeforeMaximumRounds(bool $value): void;

    public function showStatistic(): void;
}