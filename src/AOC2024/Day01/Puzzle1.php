<?php

namespace Jelle_S\AOC\AOC2024\Day01;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    $left = $right = [];
    while (($line = fgets($h)) !== false) {
      list($left[], $right[]) = explode('   ', trim($line));
    }
    fclose($h);

    return $this->calculateDistance($left, $right);
  }

  protected function calculateDistance(array $left, array $right): int {
    sort($left);
    sort($right);
    $result = 0;
    foreach ($left as $k => $v) {
      $result += abs($v - $right[$k]);
    }

    return $result;
  }
}
