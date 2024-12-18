<?php

namespace Jelle_S\AOC\AOC2024\Day18;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;
    $this->fallenBytes = new \Ds\Set();

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list ($c, $r) = array_map('intval', explode(',', $line));
      $this->fallenBytes->add([$r, $c]);
    }
    fclose($h);
    while (!$this->fallenBytes->isEmpty()) {
      $blockingByte = $this->fallenBytes->last();
      $this->fallenBytes = $this->fallenBytes->slice(0, -1);
      $result = $this->bfs(0, 0, $this->gridSize - 1, $this->gridSize - 1);
      if ($result !== -1) {
        break;
      }
    }

    return implode(',', array_reverse($blockingByte));
  }
}
