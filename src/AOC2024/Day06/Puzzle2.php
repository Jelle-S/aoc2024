<?php

namespace Jelle_S\AOC\AOC2024\Day06;

class Puzzle2 extends Puzzle1 {

  protected int $rows;
  protected int $cols;
  protected array $startpos;

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $this->startpos = [0,0];

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $startpos = strpos($line, '^');
      if ($startpos !== false) {
        $this->startpos = [count($this->grid), $startpos];
      }
      $this->grid[] = str_split($line);
    }
    fclose($h);
    $deltas = [[-1, 0], [0, 1], [1, 0], [0, -1]];
    $current_delta = 0;
    $pos = $this->startpos;
    $visited = [implode('|', $pos) => true];
    $this->rows = count($this->grid);
    $this->cols = count($this->grid[0]);
    while(true) {
      list ($drow, $dcol) = $deltas[$current_delta % 4];
      $newpos = [$pos[0] + $drow, $pos[1] + $dcol];
      if ($this->isOutsideGrid($newpos)) {
        break;
      }
      if ($this->grid[$newpos[0]][$newpos[1]] !== '#') {
        $pos = $newpos;
        $visited[implode('|', $pos)] = true;
        continue;
      }
      $current_delta++;
    }

    // Place a box in each visited position and check if it loops.
    foreach (array_keys($visited) as $visit) {
      if ($visit !== implode('|', $this->startpos) && $this->producesLoop(explode('|', $visit))) {
        $result++;
      }
    }

    return $result;
  }

  protected function isOutsideGrid($pos) {
    return $pos[0] < 0 || $pos[0] >= $this->rows || $pos[1] < 0 || $pos[1] >= $this->cols;
  }

  protected function producesLoop($obstructionPos) {
    $grid = $this->grid;
    $grid[$obstructionPos[0]][$obstructionPos[1]] = '#';
    $pos = $this->startpos;
    $deltas = [[-1, 0], [0, 1], [1, 0], [0, -1]];
    $current_delta = 0;
    $visited = [implode('|', $pos) . '@' . $deltas[$current_delta][0] . 'x' . $deltas[$current_delta][1] => true];
    while(true) {
      list ($drow, $dcol) = $deltas[$current_delta % 4];
      $newpos = [$pos[0] + $drow, $pos[1] + $dcol];
      if (isset($visited[implode('|', $newpos) . '@' . $drow . 'x' . $dcol])) {
        return true;
      }
      if ($this->isOutsideGrid($newpos)) {
        return false;
      }
      if ($grid[$newpos[0]][$newpos[1]] !== '#') {
        $pos = $newpos;
        $visited[implode('|', $pos) . '@' . $drow . 'x' . $dcol] = true;
        continue;
      }
      $current_delta++;
    }
  }
}
