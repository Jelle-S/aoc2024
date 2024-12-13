<?php

namespace Jelle_S\AOC\AOC2024\Day12;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

    protected array $grid = [];
    protected int $cols;
    protected int $rows;


    public function __construct(protected string $input) {

    }

    public function solve() {
        $result = 0;

        $h = fopen($this->input, 'r');

        while (($line = fgets($h)) !== false) {
            $line = trim($line);
            $this->grid[] = str_split($line);
        }
        fclose($h);

        $this->rows = count($this->grid);
        $this->cols = count($this->grid[0]);
        $polygons = $this->getPolygons();
        $result = array_sum(array_map(fn ($p) => $p['perimeter'] * $p['area'], $polygons));
        return $result;
    }

    protected function getPolygons(): array {
        $visited = new \Ds\Set();

        for ($r = 0; $r < $this->rows; $r++) {
            for ($c = 0; $c < $this->cols; $c++) {
                if (!$visited->contains([$r, $c])) {
                    $polygons[] = $this->getPolygon($r, $c, $visited);
                }
            }
        }

        return $polygons;
    }

    protected function getPolygon(int $r, int $c, \Ds\Set &$visited) {
        $directions = [[0, 1], [1, 0], [-1, 0], [0, -1]];
        $polygon = ['area' => 0, 'perimeter' => 0];
        $char = $this->grid[$r][$c];
        $queue = new \Ds\Queue();
        $queue->push([$r, $c]);
        $visited->add([$r, $c]);

        while (!$queue->isEmpty()) {
            list($r, $c) = $queue->pop();
            $polygon['area']++;
            foreach ($directions as $direction) {
                list($dr, $dc) = $direction;
                $nr = $r - $dr;
                $nc = $c - $dc;

                if ($nc < 0 || $nc >= $this->cols || $nr < 0 || $nr >= $this->rows) {
                    $polygon['perimeter']++;
                    continue;
                }

                if ($this->grid[$nr][$nc] !== $char) {
                    $polygon['perimeter']++;
                    continue;
                }

                if ($visited->contains([$nr, $nc])) {
                    continue;
                }

                $queue->push([$nr, $nc]);
                $visited->add([$nr, $nc]);
            }

        }

        return $polygon;
    }
}
