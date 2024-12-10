<?php

namespace Jelle_S\AOC\AOC2024\Day10;

class Puzzle2 extends Puzzle1 {

  protected function bfs($start, array $directions, $diff = 1, $destination = 9) {
    $q = new \Ds\Queue();

    $q->push($start);
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
        if (!$this->isInGrid($nr, $nc)) {
          continue;
        }

        if ($this->grid[$nr][$nc] === $this->grid[$r][$c] + $diff) {
          $q->push([$nr, $nc]);
        }
      }
    }

    return $total;
  }
}
