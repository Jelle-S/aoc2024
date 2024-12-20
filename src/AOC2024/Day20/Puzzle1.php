<?php

namespace Jelle_S\AOC\AOC2024\Day20;

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

    $path = $this->getNoCheatPath($sr, $sc, $er, $ec);
    $sortedr = $sortedc = $path;
    usort($sortedr, fn ($a, $b) => ($a[0] - $b[0] === 0 ? $a[1] - $b[1] : $a[0] - $b[0]));
    usort($sortedc, fn ($a, $b) => ($a[1] - $b[1] === 0 ? $a[0] - $b[0] : $a[1] - $b[1]));

    for ($i = 0; $i < count($path) - 1; $i++) {
      $result += (int) $this->isShortCut($sortedr[$i], $sortedr[$i + 1], 100);
      $result += (int) $this->isShortCut($sortedc[$i], $sortedc[$i + 1], 100);
    }
    return $result;
  }

  protected function getNoCheatPath(int $sr, int $sc, int $er, int $ec): array {
    $directions = [[0, 1], [1, 0], [0, -1], [-1, 0]];
    $visited = new \Ds\Set();
    $q = new \Ds\Queue();

    $q->push([$sr, $sc, 0, [[$sr, $sc, 0]]]);

    while (!$q->isEmpty()) {
      list ($r, $c, $steps, $path) = $q->pop();
      if ($r === $er && $c === $ec) {
        return $path;
      }
      foreach ($directions as $direction) {
        list($dr, $dc) = $direction;
        $nr = $r + $dr;
        $nc = $c + $dc;
        if ($visited->contains([$nr, $nc]) || !$this->isInGrid($nr, $nc) || $this->grid[$nr][$nc] === '#') {
          continue;
        }
        $visited->add([$nr, $nc]);
        $q->push([$nr, $nc, $steps + 1, [...$path, [$nr, $nc, $steps + 1]]]);
      }
    }

    return 0;
  }

  protected function isShortCut(array $a, array $b, int $saveSteps) {
    list($ra, $ca, $stepa) = $a;
    list($rb, $cb, $stepb) = $b;
    if (abs($stepa - $stepb) <= $saveSteps) {
      return false;
    }
    if ($ra !== $rb && $ca !== $cb) {
      return false;
    }

    if (abs($ra - $rb) !== 2 && abs($ca - $cb) !== 2) {
      return false;
    }

    list($r, $c) = [($ra + $rb) / 2, ($ca + $cb) / 2];
    return $this->grid[$r][$c] === '#';
  }

  protected function isInGrid($r, $c) {
    return $r >= 0 && $r < count($this->grid) && $c >= 0 && $c < count($this->grid[0]);
  }
}
