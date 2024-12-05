<?php

namespace Jelle_S\AOC\AOC2024\Day05;

class Puzzle2 extends Puzzle1 {

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
      if ($sorted_pages !== $pages) {
        $result += $sorted_pages[(count($sorted_pages) - 1) / 2];
      }
    }

    return $result;
  }
}
