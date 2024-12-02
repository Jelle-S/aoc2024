<?php

namespace Jelle_S\AOC\AOC2024\Day02;

class Puzzle2 extends Puzzle1 {

  #[\Override]
  protected function isSafeReport($line): bool {
    if (parent::isSafeReport($line)) {
      return true;
    }
    $levels = array_map('intval', explode(' ', $line));
    for ($i=0; $i < count($levels); $i++) {
      $levels_copy = $levels;
      unset($levels_copy[$i]);
      if (parent::isSafeReport(implode(' ', $levels_copy))) {
        return true;
      }
    }

    return false;
  }
}
