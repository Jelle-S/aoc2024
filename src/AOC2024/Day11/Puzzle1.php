<?php

namespace Jelle_S\AOC\AOC2024\Day11;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected int $blinks = 25;
  protected \Ds\Map $cache;

  public function __construct(protected string $input) {
    $this->cache = new \Ds\Map();
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $stones = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $stones = array_map('intval', explode(' ', $line));
    }
    fclose($h);

    foreach ($stones as $stone) {
      $result += $this->blink($stone, $this->blinks);
    }

    return $result;
  }

  protected function blink(int $stone, int $remainingBlinks): int {
    if ($remainingBlinks === 0) {
      return 1;
    }

    if ($this->cache->hasKey([$stone, $remainingBlinks])) {
      return $this->cache->get([$stone, $remainingBlinks]);
    }

    if ($stone === 0) {
      $result = $this->blink(1, $remainingBlinks - 1);
      $this->cache->put([$stone, $remainingBlinks], $result);

      return $result;
    }

    $str_stone = (string) $stone;
    $len = strlen($str_stone);
    if ($len % 2 === 0) {
      $result = $this->blink(intval(substr($str_stone, 0, $len / 2)), $remainingBlinks - 1)
        + $this->blink(intval(substr($str_stone, $len / 2)), $remainingBlinks - 1);
      $this->cache->put([$stone, $remainingBlinks], $result);

      return $result;
    }

    $result = $this->blink($stone * 2024, $remainingBlinks - 1);
    $this->cache->put([$stone, $remainingBlinks], $result);

    return $result;
  }
}
