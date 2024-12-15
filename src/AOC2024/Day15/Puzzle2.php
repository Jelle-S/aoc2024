<?php

namespace Jelle_S\AOC\AOC2024\Day15;

class Puzzle2 extends Puzzle1 {

    public function solve() {
        $result = 0;

        $parts = explode("\n\n", str_replace(['.', '#', 'O', '@'], ['..', '##', '[]', '@.'], file_get_contents($this->input)));
        foreach (explode("\n", $parts[0]) as $row => $line) {
            $robot = strpos($line, '@');
            if ($robot !== false) {
                $this->robot = [$row, $robot];
            }
            $this->grid[] = str_split($line);
        }

        $instructions = str_split(str_replace("\n", '', $parts[1]));
        foreach ($instructions as $key => $instruction) {
            $this->move(...$this->robot, ...$this->directions[$instruction]);
        }

        $result = $this->calculateGPSSum();
        return $result;
    }

    protected function move($r, $c, $dr, $dc): bool {
        if ($dr !== 0) {
            if ($this->canMoveVertical($r, $c, $dr)) {
                return $this->moveVertical($r, $c, $dr);
            }
            return false;
        }

        return $this->moveHorizontal($r, $c, $dc);
    }

    protected function canMoveVertical($r, $c, $dr, $checkneighbour = false) {
        $next_item = $this->grid[$r + $dr][$c];
        $cur_item = $this->grid[$r][$c];

        if ($next_item === '#') {
            return false;
        }

        if ($cur_item === '@') {
            if ($next_item === '.') {
                return true;
            }
            return $this->canMoveVertical($r + $dr, $c, $dr, true);
        }


        if ($cur_item === ']' && $checkneighbour) {
            if (!$this->canMoveVertical($r, $c - 1, $dr, false)) {
                return false;
            }
            return $next_item === '.' || $this->canMoveVertical($r + $dr, $c, $dr, true);
        }

        if ($cur_item === '[' && $checkneighbour) {
            if (!$this->canMoveVertical($r, $c + 1, $dr, false)) {
                return false;
            }
            return $next_item === '.' || $this->canMoveVertical($r + $dr, $c, $dr, true);
        }

        if ($next_item === '.') {
            return true;
        }

        return $this->canMoveVertical($r + $dr, $c, $dr, true);
    }

    protected function moveVertical($r, $c, $dr, $moveneighbour = false): bool {
        $next_item = $this->grid[$r + $dr][$c];
        $cur_item = $this->grid[$r][$c];

        if ($next_item !== '.') {
            $this->moveVertical($r + $dr, $c, $dr, true);
            if ($cur_item === ']' && $moveneighbour) {
                $this->moveVertical($r, $c - 1, $dr, false);
            }
            if ($cur_item === '[' && $moveneighbour) {
                $this->moveVertical($r, $c + 1, $dr, false);
            }
            $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            if ($cur_item === '@') {
                $this->robot = [$r + $dr, $c];
            }
            return true;
        }

        if ($cur_item === ']' && $moveneighbour) {
            $this->moveVertical($r, $c - 1, $dr, false);
            $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            return true;
        }

        if ($cur_item === '[' && $moveneighbour) {
            $this->moveVertical($r, $c + 1, $dr, false);
            $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            if ($cur_item === '@') {
                $this->robot = [$r + $dr, $c];
            }
            return true;
        }

        if ($next_item === '.') {
            $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            if ($cur_item === '@') {
                $this->robot = [$r + $dr, $c];
            }
            return true;
        }

        $this->moveVertical($r + $dr, $c, $dr, true);
        $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
        $this->grid[$r][$c] = '.';
        if ($cur_item === '@') {
            $this->robot = [$r + $dr, $c];
        }

        return true;
    }

    protected function moveVertical2($r, $c, $dr): bool {
        $cur_item = $this->grid[$r][$c];
        $is_robot = $cur_item === '@';

        $this->grid[$r + $dr][$c] = $this->grid[$r][$c];
        $this->grid[$r][$c] = '.';
        if ($is_robot) {
            $this->robot = [$r + $dr, $c];
            return true;
        }

        $item = $this->grid[$r + $dr][$c];
        $neighbour = $item === '[' ? [$r + $dr, $c + 1] : [$r + $d, $c - 1];
        $neighbour[] = $dr;
        return $this->moveVertical(...$neighbour);
    }

    protected function moveHorizontal($r, $c, $dc): bool {
        $item = $this->grid[$r][$c + $dc];
        if ($item === '#') {
            return false;
        }

        $is_robot = $this->grid[$r][$c] === '@';
        if ($item === '.' || (($item === '[' || $item === ']') && $this->moveHorizontal($r, $c + $dc, $dc))) {
            $this->grid[$r][$c + $dc] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            if ($is_robot) {
                $this->robot = [$r, $c + $dc];
            }
            return true;
        }

        return false;
    }

    protected function calculateGPSSum(): int {
        $sum = 0;
        foreach ($this->grid as $r => $col) {
            foreach ($this->grid[$r] as $c => $item) {
                if ($item === '[') {
                    $sum += (100 * $r) + $c;
                }
            }
        }

        return $sum;
    }
}
