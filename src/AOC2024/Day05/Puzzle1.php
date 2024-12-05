<?php

namespace Jelle_S\AOC\AOC2024\Day05;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $data = trim(file_get_contents($this->input));

    list($rules, $prints) = explode("\n\n", $data);

    $rules = explode("\n", $rules);
    $prints = explode("\n", $prints);

    foreach ($prints as $print) {
      $pages = array_map('intval', explode(',', $print));
      $sorted_pages = $pages;
      usort($sorted_pages, $this->pageSortCallback($rules));
      if ($sorted_pages === $pages) {
        $result += $pages[(count($pages) - 1) / 2];
      }
    }

    return $result;
  }

  protected function pageSortCallback($rules) {
    return function ($a, $b) use ($rules) {
      if (array_search($a . '|' . $b, $rules) !== false) {
        return -1;
      }

      return array_search($b . '|' . $a, $rules) !== false ? 1 : 0;
    };
  }
}
