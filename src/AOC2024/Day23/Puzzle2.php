<?php

namespace Jelle_S\AOC\AOC2024\Day23;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $pcs = array_filter(explode('|', str_replace(["\n", '-'], '|', file_get_contents($this->input))));
    $connections = array_fill_keys(array_unique($pcs), []);

    for ($i = 0; $i < count($pcs); $i+=2) {
      $connections[$pcs[$i]][] = $pcs[$i + 1];
      $connections[$pcs[$i + 1]][] = $pcs[$i];
    }

    $biggestLan = $this->bfs($connections);

    return implode($biggestLan);
  }

  // Try a bfs of sorts that only adds a neighbour if it has links to all items
  // in the path up until that neighbour, and only if they are also direct
  // neighbours of the starting point?
  // This works on the sample but uses way too much memory for the actual input.
  protected function bfs($connections): array {
    $biggestLan = [];
    $checked = new \Ds\Set();

    foreach ($connections as $pc => $links) {
      $q = new \Ds\Queue();
      $q->push([$pc, [$pc]]);
      while (!$q->isEmpty()) {
        list($p, $lan) = $q->pop();
        if ($checked->contains($p)) {
          continue;
        }

        $neighbours = $connections[$p];
        foreach ($neighbours as $neighbour) {
          if (in_array($neighbour, $lan) || !in_array($neighbour, $links)) {
            continue;
          }
          $add = true;
          foreach ($lan as $lanpc) {
            if (!in_array($lanpc, $connections[$neighbour])) {
              $add = false;
            }
          }
          if (!$add) {
            continue;
          }

          $q->push([$neighbour, [$neighbour, ...$lan]]);
        }
      }
      if (count($lan) > count($biggestLan)) {
        $biggestLan = $lan;
        $checked->add(...$biggestLan);
      }
    }

    sort($biggestLan);

    return $biggestLan;
  }
}
