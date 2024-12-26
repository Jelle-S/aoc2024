<?php

namespace Jelle_S\AOC\AOC2024\Day23;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {


  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $pcs = array_filter(explode('|', str_replace(["\n", '-'], '|', file_get_contents($this->input))));
    $connections = array_fill_keys(array_unique($pcs), []);

    for ($i = 0; $i < count($pcs); $i+=2) {
      $connections[$pcs[$i]][] = $pcs[$i + 1];
      $connections[$pcs[$i + 1]][] = $pcs[$i];
    }

    $lans = new \Ds\Set();

    foreach ($connections as $pc1 => $linked) {
      if (substr($pc1, 0, 1) !== 't') {
        continue;
      }
      foreach ($linked as $pc2) {
        foreach ($connections[$pc2] as $pc3) {
          if ($pc3 !== $pc1 && in_array($pc3, $connections[$pc1])) {
            $lan = [$pc1, $pc2, $pc3];
            sort($lan);
            $lans->add($lan);
          }
        }
      }
    }
    $result = $lans->count();

    return $result;
  }

}
