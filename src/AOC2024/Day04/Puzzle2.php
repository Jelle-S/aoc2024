<?php

namespace Jelle_S\AOC\AOC2024\Day04;

class Puzzle2 extends Puzzle1 {

  protected function countXMAS() {
    $count = 0;
    foreach ($this->grid as $row => $cols) {
      foreach ($cols as $col => $val) {
        if ($val !== 'A') {
          continue;
        }
        if (!isset($this->grid[$row-1][$col-1], $this->grid[$row+1][$col+1], $this->grid[$row+1][$col-1], $this->grid[$row-1][$col+1])) {
          continue;
        }
        if (
          strpos('MSM', $this->grid[$row-1][$col-1] . $this->grid[$row+1][$col+1]) !== false
          && strpos ('MSM', $this->grid[$row+1][$col-1] . $this->grid[$row-1][$col+1]) !== false
        ) {
          $count++;
        }
      }
    }

    return $count;
  }
}
