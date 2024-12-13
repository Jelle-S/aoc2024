<?php

namespace Jelle_S\AOC\AOC2024\Day13;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $buttonCosts = ['A' => 3, 'B' => 1];
  protected int $maxPresses = 100;

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $input = file_get_contents($this->input);

    $slotMachines = array_map(function ($machine) {
      $lines = explode("\n", $machine);
      return [
        'A' => [
          intval(substr($lines[0], strpos($lines[0], 'X') + 1, strpos($lines[0], ',') - strpos($lines[0], 'X') - 1)),
          intval(substr($lines[0], strpos($lines[0], 'Y') + 1)),
        ],
        'B' => [
          intval(substr($lines[1], strpos($lines[1], 'X') + 1, strpos($lines[1], ',') - strpos($lines[1], 'X') - 1)),
          intval(substr($lines[1], strpos($lines[1], 'Y') + 1)),
        ],
        'prize' => [
          intval(substr($lines[2], strpos($lines[2], 'X=') + 2, strpos($lines[2], ',') - strpos($lines[2], 'X=') - 2)),
          intval(substr($lines[2], strpos($lines[2], 'Y=') + 2)),
        ]

      ];

    }, explode("\n\n", $input));

    $result = $this->totalSmallestTokenCost($slotMachines);
    return $result;
  }

  protected function totalSmallestTokenCost($slotMachines) {
    asort($this->buttonCosts);
    $result = 0;

    foreach ($slotMachines as $slotMachine) {
      $result += $this->smallestTokenCost($slotMachine);
    }

    return $result;
  }

  protected function smallestTokenCost($slotMachine) {
    $max = [];
    $buttons = array_keys($this->buttonCosts);
    $cheapButton = reset($buttons);
    $expensiveButton = end($buttons);
    foreach ($this->buttonCosts as $button => $cost) {
      $max[$button] = min([
        intval(floor($slotMachine['prize'][0] / $slotMachine[$button][0])),
        intval(floor($slotMachine['prize'][1] / $slotMachine[$button][1])),
        $this->maxPresses
      ]);
    }
    for ($i = 0; $i <= $max[$expensiveButton]; $i++) {
      for ($j = 0; $j <= $max[$cheapButton]; $j++) {
        $position = [
          $slotMachine[$cheapButton][0] * $j + $slotMachine[$expensiveButton][0] * $i,
          $slotMachine[$cheapButton][1] * $j + $slotMachine[$expensiveButton][1] * $i,
        ];
        if ($position === $slotMachine['prize']) {
          return $j * $this->buttonCosts[$cheapButton] + $i * $this->buttonCosts[$expensiveButton];
        }
      }
    }

    return 0;
  }
}
