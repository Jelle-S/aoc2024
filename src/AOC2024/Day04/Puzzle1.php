<?php

namespace Jelle_S\AOC\AOC2024\Day04;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $grid = [];

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $this->grid[] = str_split($line);
    }
    fclose($h);

    $result = $this->countXMAS();

    return $result;
  }

  protected function countXMAS() {
    $deltas = [[-1, 1], [0, 1], [1, 1], [1, 0], [1, -1], [0, -1], [-1, -1], [-1, 0]];
    $search = ['M', 'A', 'S'];
    $count = 0;
    foreach ($this->grid as $row => $cols) {
      foreach ($cols as $col => $val) {
        if ($val !== 'X') {
          continue;
        }
        //var_dump([$row, $col]);
        foreach ($deltas as $delta) {
          $searchRow = $row;
          $searchCol = $col;
          $searchIndex = 0;
          while (true) {
            $searchRow -= $delta[0];
            $searchCol -= $delta[1];
            if (!isset($this->grid[$searchRow]) || !isset($this->grid[$searchRow][$searchCol])) {
              continue 2;
            }
            if ($this->grid[$searchRow][$searchCol] !== $search[$searchIndex]) {
              continue 2;
            }
            if ($searchIndex === 2) {
              $count++;
              continue 2;
            }
            $searchIndex++;
          }
        }
      }
    }

    return $count;
  }
}
