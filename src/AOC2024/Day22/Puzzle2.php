<?php

namespace Jelle_S\AOC\AOC2024\Day22;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');
    $secrets = [];
    while (($line = fgets($h)) !== false) {
      $line = trim($line);
      $secrets[] = intval($line);
    }
    fclose($h);

    $result = $this->getOptimalBananas($secrets);

    return $result;
  }

  protected function getOptimalBananas($secrets) {
    $bananasAfterChange = new \Ds\Map();
    foreach ($secrets as $secret) {
      $currentChanges = [];
      $processedChanges = new \Ds\Set();
      $oldSecret = $secret;
      for ($i = 0; $i < 2000; $i++) {
        $oldPrice = $oldSecret % 10;
        $newSecret = $this->calculateSecret($oldSecret);
        $newPrice = $newSecret % 10;
        $change = $newPrice - $oldPrice;
        $currentChanges[] = $change;
        if ($i >= 3) {
          if (!$processedChanges->contains($currentChanges)) {
            $processedChanges->add($currentChanges);
            if (!$bananasAfterChange->hasKey($currentChanges)) {
              $bananasAfterChange->put($currentChanges, 0);
            }
            $bananasAfterChange->put($currentChanges, $bananasAfterChange->get($currentChanges) + $newPrice);
          }
          array_shift($currentChanges);
        }
        $oldSecret = $newSecret;
      }
    }

    return max($bananasAfterChange->values()->toArray());
  }
}
