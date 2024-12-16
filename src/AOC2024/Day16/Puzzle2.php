<?php

namespace Jelle_S\AOC\AOC2024\Day16;

class Puzzle2 extends Puzzle1 {

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

    $result = $this->getBestTilesCount($sr, $sc, $er, $ec);

    return $result;
  }

  protected function getBestTilesCount(int $sr, int $sc, int $er, int $ec): int {
    $q = new \Ds\PriorityQueue();
    $visited = new \Ds\Set();
    $directions = [[0, 1], [-1, 0], [0, -1], [1, 0]];
    $path = new \Ds\Set();
    $path->add([$sr, $sc]);
    $tiles = new \Ds\Set();
    $best_score = null;
    $q->push([$sr, $sc, $directions[0], 0, $path], 1);
    while (!$q->isEmpty()) {
      list($r, $c, list($pdr, $pdc), $score, $path) = $q->pop();
      $visited->add([$r, $c, [$pdr, $pdc]]);
      if ($r === $er && $c === $ec) {
        if (is_null($best_score)) {
          $best_score = $score;
          $tiles = $path;
        }
        if ($score > $best_score) {
          break;
        }
        $tiles = $tiles->merge($path);
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
        $new_path = $path->copy();
        $new_path->add([$nr, $nc]);
        // Misleading challenge... Turning costs 1000 points, but turning
        // _and then_ taking a step costs 1001 points...
        $cost = ($dr === $pdr && $dc === $pdc) ? 1 : 1001;
        $q->push([$nr, $nc, [$dr, $dc], $score + $cost, $new_path], -$cost-$score);
      }
    }

    return $tiles->count();
  }
}
