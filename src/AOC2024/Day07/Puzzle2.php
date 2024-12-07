<?php

namespace Jelle_S\AOC\AOC2024\Day07;

class Puzzle2 extends Puzzle1 {

  protected function isValidEquation($result, int ...$ints) {
      if (count($ints) === 1) {
          return $ints[0] === $result;
      }
      $int1 = array_shift($ints);
      $int2 = array_shift($ints);
      return $this->isValidEquation($result, $int1 + $int2, ...$ints)
          || $this->isValidEquation($result, $int1 * $int2, ...$ints)
          || $this->isValidEquation($result, intval($int1 . $int2), ...$ints);
  }
}