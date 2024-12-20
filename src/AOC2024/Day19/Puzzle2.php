<?php

namespace Jelle_S\AOC\AOC2024\Day19;

class Puzzle2 extends Puzzle1 {

  protected \Ds\Map $cache;

  public function solve() {
    $result = 0;

    $this->cache = new \Ds\Map();

    list($towels, $patterns) = explode("\n\n", file_get_contents($this->input));

    $towels = explode(', ', trim($towels));
    usort($towels, fn ($a, $b) => strlen($b) - strlen($a));
    $patterns = explode("\n", trim($patterns));

    foreach ($patterns as $pattern) {
      $result += $this->countCombos($towels, $pattern);
    }
    return $result;
  }

  protected function countCombos($towels, $pattern) {
    if ($this->cache->hasKey($pattern)) {
      return $this->cache->get($pattern);
    }
    $result = 0;
    foreach ($towels as $towel) {
      if ($towel === $pattern) {
        $result++;
      }
      if (strpos($pattern, $towel) === 0) {
         $result += $this->countCombos($towels, substr($pattern, strlen($towel)));
      }
    }

    $this->cache->put($pattern, $result);

    return $result;
  }
}
