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

  protected function getShortCutCount($path) {
    $pathByR = $pathByC = [];

    foreach ($path as $point) {
      $pathByR[$point[0]] ??= [];
      $pathByR[$point[0]][] = $point;
      $pathByC[$point[1]] ??= [];
      $pathByC[$point[1]][] = $point;
    }
    $shortCuts = new \Ds\Set();
    $skip = 76;
    for ($i = 0; $i < count($path) - $skip; $i++) {
      $start = $path[$i];
      for ($j = $start[0] - 21; $j <= $start[0] + 21; $j++) {
        if (!isset($pathByR[$j])) {
          continue;
        }
        foreach ($pathByR[$j] as $dest) {
          if ($this->manhattanDistance($path[$i], $dest) > 21) {
            continue;
          }
          if ($dest[2] - $start[2] < $skip) {
            continue;
          }
          $shortCuts->add([$path[$i], $dest]);
        }
      }

      for ($j = $start[1] - 21; $j <= $start[1] + 21; $j++) {
        if (!isset($pathByC[$j])) {
          continue;
        }
        foreach ($pathByC[$j] as $dest) {
          if ($this->manhattanDistance($path[$i], $dest) > 21) {
            continue;
          }
          if ($dest[2] - $start[2] < $skip) {
            continue;
          }
          $shortCuts->add([$path[$i], $dest]);
        }
      }
    }
    print implode(
      "\n",
      array_map(
        function ($v) {
          return implode(
            ': ',
            array_map(
              fn ($v2) => implode(', ', $v2),
              $v
            )
          );
        },
        $shortCuts->toArray()
      )
    );
    return $shortCuts->count();
  }

  protected function manhattanDistance($a, $b) {
    return abs($a[0] - $b[0]) + abs($a[1] - $b[1]);
  }
}
