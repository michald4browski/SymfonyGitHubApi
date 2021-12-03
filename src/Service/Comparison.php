<?php

namespace App\Service;

class Comparison implements \JsonSerializable
{
    private string $comparedParam;

    private int $quantityDifference;

    private string $percentageDifference;


    public function getComparedParam(): string
    {
        return $this->comparedParam;
    }

    public function setComparedParam(string $comparedParam): void
    {
        $this->comparedParam = $comparedParam;
    }

    public function getQuantityDifference(): int
    {
        return $this->quantityDifference;
    }

    public function setQuantityDifference(int $quantityDifference): void
    {
        $this->quantityDifference = $quantityDifference;
    }

    public function getPercentageDifference(): string
    {
        return $this->percentageDifference;
    }

    public function setPercentageDifference(string $percentageDifference): void
    {
        $this->percentageDifference = $percentageDifference;
    }


    public function jsonSerialize()
    {
        return [
            'comparedParam' => $this->getComparedParam(),
            'quantityDifference' => $this->getQuantityDifference(),
            'percentageDifference' => $this->getPercentageDifference(),
        ];
    }
}