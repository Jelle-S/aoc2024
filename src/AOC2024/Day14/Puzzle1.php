<?php

namespace Jelle_S\AOC\AOC2024\Day14;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

    protected int $time = 100;
    protected int $height = 103;
    protected int $width = 101;

    public function __construct(protected string $input) {

    }

    public function solve() {
        $result = 0;
        $h = fopen($this->input, 'r');
        $quadrants = [0, 0, 0, 0];
        while (($line = fgets($h)) !== false) {
            $line = trim($line);
            $matches = [];
            preg_match_all('/-?\d+/', $line, $matches);
            $quadrant = $this->getEndQuadrant(
                [intval($matches[0][0]), intval($matches[0][1])],
                [intval($matches[0][2]), intval($matches[0][3])]
            );
            if ($quadrant === false) {
                continue;
            }
            $quadrants[$quadrant]++;
        }
        fclose($h);
        $result = array_product($quadrants);
        return $result;
    }

    protected function getEndQuadrant($p, $v) {
        $endPos = [
            $p[0] + ($v[0] * $this->time) % $this->width,
            $p[1] + ($v[1] * $this->time) % $this->height,
        ];

        $endPos[0] = ($endPos[0] % $this->width + $this->width) % $this->width;
        $endPos[1] = ($endPos[1] % $this->height + $this->height) % $this->height;

        $middleRow = ($this->height - 1) / 2;
        $middleCol = ($this->width - 1) / 2;

        if ($endPos[0] === $middleCol || $endPos[1] === $middleRow) {
            return false;
        }

        $q1 = $endPos[0] > $middleCol ? 1 : 0;
        $q2 = $endPos[1] > $middleRow ? 1 : 0;

        switch ($q1.$q2) {
            case "00":
                return 0;
            case "01":
                return 1;
            case "11":
                return 2;
            case "10":
                return 3;
        }
    }
}
