<?php

namespace Jelle_S\AOC\AOC2024\Day16;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $grid = [];

  public function __construct(protected string $input) {
  }

  public function solve() {
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

    $result = $this->getBestPathScore($sr, $sc, $er, $ec);

    return $result;
  }

  protected function getBestPathScore(int $sr, int $sc, int $er, int $ec): int {
    $q = new \Ds\PriorityQueue();
    $visited = new \Ds\Set();
    $directions = [[0, 1], [-1, 0], [0, -1], [1, 0]];
    $q->push([$sr, $sc, $directions[0], 0], 1);
    while (!$q->isEmpty()) {
      list($r, $c, list($pdr, $pdc), $score) = $q->pop();
      $visited->add([$r, $c, [$pdr, $pdc]]);
      if ($r === $er && $c === $ec) {
        return $score;
      }
      foreach ($directions as $direction) {
        list($dr, $dc) = $direction;
        // Don't turn back.
        if (($dc === 0 && $pdc === 0 && $dr === -$pdr) || ($dr === 0 && $pdr === 0 && $dc === -$pdc)) {
          continue;
        }
        $nr = $r + $dr;
        $nc = $c + $dc;
        // Don't turn if we can't. Don't revisit in the same direction.
        if ($this->grid[$nr][$nc] === '#' || $visited->contains([$nr, $nc, [$dr, $dc]])) {
          continue;
        }
        // Misleading challenge... Turning costs 1000 points, but turning
        // _and then_ taking a step costs 1001 points...
        $cost = ($dr === $pdr && $dc === $pdc) ? 1 : 1001;
        $q->push([$nr, $nc, [$dr, $dc], $score + $cost], -$cost-$score);
      }
    }

    return 0;
  }

}
