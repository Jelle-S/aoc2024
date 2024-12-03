<?php

namespace Jelle_S\AOC\AOC2024\Day03;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    $memory = 'do()';
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $memory .= $line;
    }
    fclose($h);

    $relevant_parts = [];
    preg_match_all("/((do\(\))(?<line>.+?)(?=don't|$))*/", $memory, $relevant_parts);
    $result += array_sum(array_map([$this, 'lineResult'], array_filter($relevant_parts['line'])));
    return $result;
  }
}
