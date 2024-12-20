<?php

namespace Jelle_S\AOC\AOC2024\Day19;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    list($towels, $patterns) = explode("\n\n", file_get_contents($this->input));

    $towels = explode(', ', trim($towels));
    usort($towels, fn ($a, $b) => strlen($b) - strlen($a));
    $patterns = explode("\n", trim($patterns));

    foreach ($patterns as $pattern) {
      if ($this->canMakePattern($towels, $pattern)) {
        $result++;
      }
    }
    return $result;
  }

  protected function canMakePattern($towels, $pattern) {
    foreach ($towels as $towel) {
      if ($towel === $pattern) {
        return true;
      }
      if (strpos($pattern, $towel) === 0) {
         if ($this->canMakePattern($towels, substr($pattern, strlen($towel)))) {
           return true;
         }
      }
    }

    return false;
  }
}
