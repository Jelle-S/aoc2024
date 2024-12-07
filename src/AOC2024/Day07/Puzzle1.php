<?php

namespace Jelle_S\AOC\AOC2024\Day07;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    while (($line = fgets($h)) !== false) {
        $line = trim($line);
        $parts = explode(': ', $line);
        if ($this->isValidEquation(intval($parts[0]), ...array_map('intval', explode(' ', $parts[1])))) {
            $result += intval($parts[0]);
        }
    }
    fclose($h);

    return $result;
  }
  
  protected function isValidEquation($result, int ...$ints) {
      if (count($ints) === 1) {
          return $ints[0] === $result;
      }
      $int1 = array_shift($ints);
      $int2 = array_shift($ints);
      return $this->isValidEquation($result, $int1 + $int2, ...$ints)
          || $this->isValidEquation($result, $int1 * $int2, ...$ints);
  }
  
  
}