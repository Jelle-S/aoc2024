<?php

namespace Jelle_S\AOC\AOC2024\Day08;

class Puzzle2 extends Puzzle1 {

    #[\Override]
    protected function getAntinodesCouple($posA, $posB) {
        $diff = [$posA[0] - $posB[0], $posA[1] - $posB[1]];
        $candidates = [];
        $candidate = $posA;

        while ($this->isWithinGrid($candidate)) {
            $candidates[] = $candidate;
            $candidate = [$candidate[0] + $diff[0], $candidate[1] + $diff[1]];
        }

        $candidate = $posB;

        while ($this->isWithinGrid($candidate)) {
            $candidates[] = $candidate;
            $candidate = [$candidate[0] - $diff[0], $candidate[1] - $diff[1]];
        }

        return $candidates;
    }

    protected function isWithinGrid($pos) {
        return $pos[0] >= 0 && $pos[0] < $this->rows && $pos[1] >= 0 && $pos[1] < $this->cols;
    }
}
