<?php

namespace Jelle_S\AOC\AOC2024\Day06;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $grid;

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $pos = [0,0];

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $startpos = strpos($line, '^');
      if ($startpos !== false) {
        $pos = [count($this->grid), $startpos];
      }
      $this->grid[] = str_split($line);
    }
    fclose($h);
    $deltas = [[-1, 0], [0, 1], [1, 0], [0, -1]];
    $current_delta = 0;
    $visited = [implode('|', $pos) => true];
    $rows = count($this->grid);
    $cols = count($this->grid[0]);
    while(true) {
      list ($drow, $dcol) = $deltas[$current_delta % 4];
      $newpos = [$pos[0] + $drow, $pos[1] + $dcol];
      if ($newpos[0] < 0 || $newpos[0] >= $rows || $newpos[1] < 0 || $newpos[1] >= $cols) {
        break;
      }
      if ($this->grid[$newpos[0]][$newpos[1]] !== '#') {
        $pos = $newpos;
        $visited[implode('|', $pos)] = true;
        continue;
      }
      $current_delta++;
    }

    return count($visited);
  }
}
