<?php

namespace ExposureSoftware\Versioner\Concerns;

use ExposureSoftware\Versioner\Comparison;
use ExposureSoftware\Versioner\VersionString;

trait Comparisons
{
    public function isLessThan(VersionString|string $operand): bool
    {
        if (is_string($operand)) {
            $operand = new VersionString($operand);
        }

        return $this->compare($operand) === Comparison::LESS_THAN;
    }

    public function isGreaterThan(VersionString|string $operand): bool
    {
        if (is_string($operand)) {
            $operand = new VersionString($operand);
        }

        return $this->compare($operand) === Comparison::GREATER_THAN;
    }

    protected function compare(VersionString $to): Comparison
    {
        if ($this->isEqualTo($to)) {
            return Comparison::EQUAL;
        }

        if ($this->major > $to->major) {
            return Comparison::GREATER_THAN;
        }

        if ($this->minor > $to->minor) {
            return Comparison::GREATER_THAN;
        }

        if ($this->patch > $to->patch) {
            return Comparison::GREATER_THAN;
        }

        return Comparison::LESS_THAN;
    }

    public function isEqualTo(VersionString $operand): bool
    {
        return $this->major === $operand->major && $this->minor === $operand->minor && $this->patch === $operand->patch;
    }
}