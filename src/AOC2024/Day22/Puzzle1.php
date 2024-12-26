<?php

namespace Jelle_S\AOC\AOC2024\Day22;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $secrets = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $secrets[] = $this->getSecretAt(intval($line), 2000);
    }
    fclose($h);

    $result = array_sum($secrets);

    return $result;
  }

  protected function getSecretAt($value, $iteration) {
    if ($iteration === 0) {
      return $value;
    }
    $result =  $this->getSecretAt($this->calculateSecret($value), $iteration - 1);

    return $result;
  }

  protected function calculateSecret($value) {
    $value = (($value << 6) ^ $value) & 16777215;
    $value = (($value >> 5) ^ $value) & 16777215;
    $value = (($value << 11) ^ $value) & 16777215;

    return $value;
  }
}
