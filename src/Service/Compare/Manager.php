<?php

namespace app\Service\Compare;

class Manager implements CompareResultInterface
{
    private $comparedValue;

    private $allowedValues = [0, 1, -1];

    public function setComparedValue(int $value): void
    {
        if (!in_array($value, $this->allowedValues)) {
            throw new \LogicException('Wrong compared value');
        }

        $this->comparedValue = $value;
    }

    public function isMore(): bool
    {
        $this->validate();

        return $this->comparedValue === 1;
    }

    public function isLess(): bool
    {
        $this->validate();

        return $this->comparedValue === -1;
    }

    public function isEqual(): bool
    {
        $this->validate();

        return $this->comparedValue === 0;
    }

    private function validate(): void
    {
        if (is_null($this->comparedValue)) {
            throw new \LogicException('Compared value is null');
        }
    }
}