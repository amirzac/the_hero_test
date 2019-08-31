<?php

namespace app\Service\Compare;

interface CompareResultInterface
{
    public function setComparedValue(int $value): void;

    public function isMore(): bool;

    public function isLess(): bool;

    public function isEqual(): bool;
}