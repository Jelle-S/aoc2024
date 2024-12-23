<?php

namespace Jelle_S\AOC\AOC2024\Day21;

class Puzzle2 extends Puzzle1 {

  #[\Override]
  protected function getComplexity(string $code) {
    $buttons = str_split($code);
    $codeVal = intval(substr($code, 0, -1));
    $directional = array_fill(0, 25, 'directional');
    $presses = $this->getButtonPresses($buttons, ['numeric', ...$directional]);
    return $codeVal * strlen($presses);
  }
}
