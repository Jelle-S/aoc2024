<?php

namespace Jelle_S\AOC\AOC2024\Day20;

class Puzzle2 extends Puzzle1 {

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
    return $this->getShortCutCount($path);
  }

  protected function getShortCutCount(array $path) {
    $pathMap = new \Ds\Map();
    foreach ($path as $pos) {
      list($r, $c, $steps) = $pos;
      $pathMap->put([$r, $c], $steps);
    }

    $count = 0;
    foreach ($path as $pos) {
      list ($r, $c) = $pos;
      for ($distance = 2; $distance <= 20; $distance++) {
        for ($dr = 0; $dr <= $distance; $dr++) {
          // Manhattan distance: Sum of dc and dr is the full distance.
          $dc = $distance - $dr;
          // Set because if $dr = 0, $r+$dr === $r-$dr.
          $neighbours = new \Ds\Set();
          $neighbours->add(...[
              [$r + $dr, $c + $dc],
              [$r + $dr, $c - $dc],
              [$r - $dr, $c + $dc],
              [$r - $dr, $c - $dc],
          ]);
          foreach ($neighbours as $n) {
            list ($nr, $nc) = $n;
            if (!$this->isInGrid($nr, $nc) || $this->grid[$nr][$nc] === '#') {
              continue;
            }
            if ($pathMap->get([$r, $c]) - $pathMap->get([$nr, $nc]) >= 100 + $distance) {
              $count++;
            }
          }
        }
      }
    }

    return $count;
  }
}
