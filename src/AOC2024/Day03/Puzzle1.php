<?php

namespace Jelle_S\AOC\AOC2024\Day03;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $result += $this->lineResult($line);
    }
    fclose($h);

    return $result;
  }

  protected function lineResult($line): int {
    $mults = [];
    preg_match_all('/mul\((\d{1,3}),(\d{1,3})\)/', $line, $mults);

    return array_sum(array_map('array_product', array_map(null, array_map('intval', $mults[1]), array_map('intval', $mults[2]))));
  }
}
