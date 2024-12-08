<?php

namespace Jelle_S\AOC\AOC2024\Day08;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

    protected int $rows;
    protected int $cols;

    public function __construct(protected string $input) {

    }

    public function solve() {
        $result = 0;

        $h = fopen($this->input, 'r');
        $row = 0;
        $antinodes = new \Ds\Set();
        $antennas = [];
        while (($line = fgets($h)) !== false) {
            $line = trim($line);
            $this->cols = strlen($line);
            $freqs = array_filter(str_split($line), fn($c) => $c !== '.');
            foreach ($freqs as $col => $antenna) {
                $antennas[$antenna] = $antennas[$antenna] ?? [];
                $antennas[$antenna][] = [$row, $col];
            }
            $row++;
        }
        fclose($h);

        $this->rows = $row;
        foreach ($antennas as $freq => $positions) {
            $antinodes->add(...$this->getAntinodes($positions));
        }
        $result = $antinodes->count();

        return $result;
    }

    protected function getAntinodes($positions) {
        $antinodes = new \Ds\Set();
        $len = count($positions);
        for ($i = 0; $i < $len - 1; $i++) {
            for ($j = $i + 1; $j < $len; $j++) {
                $antinodes->add(...$this->getAntinodesCouple($positions[$i], $positions[$j]));
            }
        }
        return $antinodes->toArray();
    }

    protected function getAntinodesCouple($posA, $posB) {
        $diff = [$posA[0] - $posB[0], $posA[1] - $posB[1]];
        return array_filter(
            [[$posA[0] + $diff[0], $posA[1] + $diff[1]], [$posB[0] - $diff[0], $posB[1] - $diff[1]]],
            fn ($pos) => $pos[0] >= 0 && $pos[0] < $this->rows && $pos[1] >= 0 && $pos[1] < $this->cols
        );
    }
}
