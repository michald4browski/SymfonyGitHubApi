<?php

namespace App\Validation;

class Violation implements \JsonSerializable
{
    private string $parameter;

    private string $constraint;

    public function getParameter(): string
    {
        return $this->parameter;
    }

    public function setParameter(string $parameter): void
    {
        $this->parameter = $parameter;
    }

    public function getConstraint(): string
    {
        return $this->constraint;
    }

    public function setConstraint(string $constraint): void
    {
        $this->constraint = $constraint;
    }

    public function jsonSerialize()
    {
        return [
            'paremeter' => $this->getParameter(),
            'constraint' => $this->getConstraint(),
        ];
    }
}