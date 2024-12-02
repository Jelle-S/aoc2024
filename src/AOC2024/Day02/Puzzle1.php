<?php

namespace Jelle_S\AOC\AOC2024\Day02;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $result += (int) $this->isSafeReport($line);
    }
    fclose($h);

    return $result;
  }

  protected function isSafeReport($line): bool {
    $levels = array_map('intval', explode(' ', $line));
    $levels_copy = $levels;
    sort($levels_copy);
    if ($levels !== $levels_copy && $levels !== array_reverse($levels_copy)) {
      return false;
    }

    $prev = array_shift($levels);
    foreach ($levels as $l) {
      $diff = abs($prev - $l);
      $prev = $l;
      if ($diff < 1 || $diff > 3) {
        return false;
      }
    }

    return true;
  }
}
