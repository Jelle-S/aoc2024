<?php

namespace Jelle_S\AOC\AOC2024\Day18;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected \Ds\Set $fallenBytes;
  protected int $gridSize = 71;
  protected int $bytesWillFall = 1024;

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;
    $this->fallenBytes = new \Ds\Set();

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      list ($c, $r) = array_map('intval', explode(',', $line));
      $this->fallenBytes->add([$r, $c]);
      if ($this->fallenBytes->count() >= $this->bytesWillFall) {
        break;
      }
    }
    fclose($h);
    $result = $this->bfs(0, 0, $this->gridSize - 1, $this->gridSize - 1);

    return $result;
  }

  protected function bfs($sr, $sc, $er, $ec) {
    $q = new \Ds\Queue();
    $q->push([$sr, $sc, 0]);
    $visited = new \Ds\Set();
    $visited->add([$sr, $sc]);
    $directions = [[1, 0], [0, 1], [-1, 0], [0, -1]];

    while(!$q->isEmpty()) {
      list($r, $c, $steps) = $q->pop();
      if ($r === $er && $c === $ec) {
        return $steps;
      }
      foreach ($directions as $direction) {
        list ($dr, $dc) = $direction;
        $nr = $r + $dr;
        $nc = $c + $dc;
        if (!$this->isInGrid($nr, $nc) || $this->fallenBytes->contains([$nr, $nc]) || $visited->contains([$nr, $nc])) {
          continue;
        }
        $visited->add([$nr, $nc]);
        $q->push([$nr, $nc, $steps + 1]);
      }
    }
    return -1;
  }

  protected function isInGrid(int $r, int $c) {
    return $r >= 0 && $r < $this->gridSize && $c >= 0 && $c < $this->gridSize;
  }

  protected function printGrid() {
    for ($r = 0; $r < $this->gridSize; $r++) {
      for ($c = 0; $c < $this->gridSize; $c++) {
        print $this->fallenBytes->contains([$r, $c]) ? '#' : '.';
      }
      print PHP_EOL;
    }
  }
}
