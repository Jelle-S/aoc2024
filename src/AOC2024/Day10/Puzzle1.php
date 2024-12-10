<?php

namespace Jelle_S\AOC\AOC2024\Day10;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $grid = [];

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $row = 0;
    $trailheads = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $this->grid[] = array_map('intval', str_split($line));
      foreach ($this->grid[$row] as $col => $val) {
        if ($val === 0) {
          $trailheads[] = [$row, $col];
        }
      }
      $row++;
    }
    fclose($h);


    return $this->sumTrailheadScores($trailheads);
  }

  protected function sumTrailheadScores(array $trailheads) {
    $directions = [[0, -1], [0, 1], [-1, 0], [1,0]];
    $sum = 0;
    foreach ($trailheads as $trailhead) {
      $sum += $this->bfs($trailhead, $directions);
    }

    return $sum;
  }


  protected function bfs($start, array $directions, $diff = 1, $destination = 9) {
    $q = new \Ds\Queue();

    $q->push($start);
    $visited = new \Ds\Set();
    $visited->add($start);
    $total = 0;

    while (!$q->isEmpty()) {
      $pos = $q->pop();
      list($r, $c) = $pos;
      if ($this->grid[$r][$c] === $destination) {
        $total++;
        continue;
      }
      foreach ($directions as $direction) {
        list($dr, $dc) = $direction;
        list($nr, $nc) = [$r - $dr, $c - $dc];
        if (!$this->isInGrid($nr, $nc) || $visited->contains([$nr, $nc])) {
          continue;
        }

        if ($this->grid[$nr][$nc] === $this->grid[$r][$c] + $diff) {
          $visited->add([$nr, $nc]);
          $q->push([$nr, $nc]);
        }
      }
    }

    return $total;
  }

  protected function isInGrid($r, $c) {
    return array_key_exists($r, $this->grid) && array_key_exists($c, $this->grid[$r]);
  }
}
