<?php

namespace Jelle_S\AOC\AOC2024\Day01;

class Puzzle2 extends Puzzle1 {

  protected function calculateDistance(array $left, array $right): int {
    sort($left);
    sort($right);
    $result = 0;
    foreach ($left as $l) {
      $occurence = 0;
      foreach ($right as $r) {
        if ($l === $r) {
          $occurence++;
        }
        if ($r > $l) {
          break;
        }
      }
      $result += $l * $occurence;
    }
    return $result;
  }
}
