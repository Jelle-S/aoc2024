<?php

namespace Jelle_S\AOC\AOC2024\Day03;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    // return $this->solveAlternative();
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

  public function solveAlternative() {
    $result = 0;

    $h = fopen($this->input, 'r');

    $memory = '';
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $memory .= $line;
    }
    fclose($h);

    $relevant_parts = [];
    $parts = explode("don't()", $memory);
    $relevant_parts[] = array_shift($parts);
    foreach ($parts as $memory_part) {
      $p = explode("do()", $memory_part);
      array_shift($p);
      $relevant_parts[] = implode('', $p);
    }
    $result += array_sum(array_map([$this, 'lineResult'], array_filter($relevant_parts)));
    return $result;
  }
}
