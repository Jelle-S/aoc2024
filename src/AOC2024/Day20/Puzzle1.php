<?php

namespace Jelle_S\AOC\AOC2024\Day20;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $grid = [];

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;
    $result = 0;

    $h = fopen($this->input, 'r');
    $sr = $sc = $er = $ec = 0;
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $spos = strpos($line, 'S');
      $epos = strpos($line, 'E');
      if ($spos !== false) {
        $sr = count($this->grid);
        $sc = $spos;
      }
      if ($epos !== false) {
        $er = count($this->grid);
        $ec = $epos;
      }
      $this->grid[] = str_split($line);
    }
    fclose($h);

    $path = $this->getNoCheatPath($sr, $sc, $er, $ec);

    foreach (array_slice($path, 0, -100) as $step => $cheatPoint) {
      if ($this->hasShortCut($cheatPoint, array_slice($path, $step))) {
        $result++;
      }
    }

    return $result;
  }

  protected function getNoCheatPathTime(int $sr, int $sc, int $er, int $ec): int {
    $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
    $visited = new \Ds\Set();
    $q = new \Ds\Queue();

    $q->push([$sr, $sc, 0, [$sr, $sc]]);

    while (!$q->isEmpty()) {
      list ($r, $c, $steps, $path) = $q->pop();
      if ($r === $er && $c === $ec) {
        return $steps;
      }
      foreach ($directions as $direction) {
        list($dr, $dc) = $direction;
        $nr = $r + $dr;
        $nc = $c + $dc;
        if ($visited->contains([$nr, $nc]) || !$this->isInGrid($nr, $nc) || $this->grid[$nr][$nc] === '#') {
          continue;
        }
        $visited->add([$nr, $nc]);
        $q->push([$nr, $nc, $steps + 1, [...$path, [$nr, $nc]]]);
      }
    }

    return 0;
  }

  protected function countCheatPathsFasterThan(int $sr, int $sc, int $er, int $ec, int $time): int {
    $count = 0;
    $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
    $visited = new \Ds\Set();
    $q = new \Ds\Queue();

    $q->push([$sr, $sc, 0, [], [[$sr, $sc]]]);

    while (!$q->isEmpty()) {
      list ($r, $c, $steps, $usedCheat) = $q->pop();
      if ($r === $er && $c === $ec) {
        $count++;
        continue;
      }
      foreach ($directions as $direction) {
        list($dr, $dc) = $direction;
        $nr = $r + $dr;
        $nc = $c + $dc;
        if (!$this->isInGrid($nr, $nc)) {
          continue;
        }
        if ($this->grid[$nr][$nc] !== '#' && !$visited->contains([$nr, $nc, $usedCheat]) && $steps + 1 <= $time) {
          $visited->add([$nr, $nc, $usedCheat]);
          $q->push([$nr, $nc, $steps + 1, $usedCheat]);
        }
        if ($usedCheat) {
          continue;
        }
        $cnr = $nr + $dr;
        $cnc = $nc + $dc;
        if (!$this->isInGrid($cnr, $cnc)) {
          continue;
        }
        if ($this->grid[$cnr][$cnc] !== '#' && !$visited->contains([$cnr, $cnc, true]) && $steps + 2 <= $time) {
          $visited->add([$cnr, $cnc, [$r, $c, $cnr, $cnc]]);
          $q->push([$cnr, $cnc, $steps + 2, [$r, $c, $cnr, $cnc]]);
          continue;
        }
      }
    }

    return $count;
  }

  protected function isInGrid($r, $c) {
    return $r >= 0 && $r < count($this->grid) && $c >= 0 && $c < count($this->grid[0]);
  }
}
