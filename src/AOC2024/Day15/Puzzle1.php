<?php

namespace Jelle_S\AOC\AOC2024\Day15;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

    protected array $grid;
    protected array $directions = [
        '^' => [-1, 0],
        '>' => [0, 1],
        'v' => [1, 0],
        '<' => [0, -1],
    ];
    protected array $robot = [0, 0];

    public function __construct(protected string $input) {

    }

    public function solve() {
        $result = 0;

        $parts = explode("\n\n", file_get_contents($this->input));
        foreach(explode("\n", $parts[0]) as $row => $line) {
            $robot = strpos($line, '@');
            if ($robot !== false) {
                $this->robot = [$row, $robot];
            }
            $this->grid[] = str_split($line);
        }
        $instructions = str_split(str_replace("\n", '', $parts[1]));

        foreach ($instructions as $instruction) {
            $this->move(...$this->robot, ...$this->directions[$instruction]);
        }

        $result = $this->calculateGPSSum();
        $this->printGrid();
        return $result;
    }

    protected function move($r, $c, $dr, $dc): bool {
        $is_robot = $this->grid[$r][$c] === '@';
        $item = $this->grid[$r + $dr][$c + $dc];
        if ($item === '#') {
            return false;
        }

        if ($item === '.' || ($item === 'O' && $this->move($r + $dr, $c + $dc, $dr, $dc))) {
            $this->grid[$r + $dr][$c + $dc] = $this->grid[$r][$c];
            $this->grid[$r][$c] = '.';
            if ($is_robot) {
                $this->robot = [$r + $dr, $c + $dc];
            }
            return true;
        }

        return false;
    }

    protected function calculateGPSSum(): int {
        $sum = 0;
        foreach($this->grid as $r => $col) {
            foreach($this->grid[$r] as $c => $item) {
                if ($item === 'O') {
                    $sum += (100 * $r) + $c;
                }
            }
        }

        return $sum;
    }

    protected function printGrid() {
        print implode("\n", array_map(fn ($v) => implode('', $v), $this->grid)) . PHP_EOL;
    }
}
