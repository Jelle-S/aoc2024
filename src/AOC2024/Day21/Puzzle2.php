<?php

namespace Jelle_S\AOC\AOC2024\Day21;

class Puzzle2 extends Puzzle1 {

  #[\Override]
  protected function getComplexity(string $code) {
    $buttons = str_split($code);
    $codeVal = intval(substr($code, 0, -1));
    $numericPresses = $this->getButtonPresses($buttons, ['numeric']);
    $this->fillcache();
    $lengths = array_map(fn($v) => $this->calculateLength($v), $numericPresses);
    return $codeVal * min($lengths);
  }

  protected function calculateLength($combo, $depth = 25) {
    if ($this->cache->hasKey([__METHOD__, ...func_get_args()])) {
      return $this->cache->get([__METHOD__, ...func_get_args()]);
    }
    if ($depth === 0) {
      // Last depth. The total length is the sum of the lengths of the paths
      // between all buttons in this combo, starting from 'A'.
      $steps = array_slice(array_map(null, str_split('A' . $combo), str_split($combo)), 0, strlen($combo));
      return array_sum(array_map(fn ($step) => $this->cache->get(['length', $step[0], $step[1]], 1), $steps));
    }
    $length = 0;
    // Not at last depth yet, need to expand the button presses, which means
    // expand the moves between the button presses, always starting from 'A'.
    $steps = array_slice(array_map(null, str_split('A' . $combo), str_split($combo)), 0, strlen($combo));
    // Expand the steps: Multiple paths possible, add the minimal length for
    // the expanded steps.
    foreach ($steps as $step) {
      $expandedPaths = $this->cache->get(['path', $step[0], $step[1]]);
      $length += min(array_map(fn ($path) => $this->calculateLength($path, $depth - 1), $expandedPaths));
    }

    $this->cache->put([__METHOD__, ...func_get_args()], $length);

    return $length;
  }

  protected function getButtonPresses($buttons, $padTypes) {
    if (!$padTypes) {
      return implode('', $buttons);
    }
    $padType = array_shift($padTypes);
    $pos = $this->keyPads[$padType]['A'];

    $presses = [''];
    foreach ($buttons as $button) {
      $paths = $this->getPaths($pos, $this->keyPads[$padType][$button], $padType);
      $pathPresses = [];
      foreach ($paths as $path) {
        $pathPresses[] = $this->getButtonPresses(str_split($path), $padTypes);
      }
      $newPresses = [];
      foreach($presses as $press) {
        foreach ($pathPresses as $pathPress) {
          $newPresses[] = $press . $pathPress;
        }
      }
      $presses = $newPresses;
      $pos = $this->keyPads[$padType][$button];
    }
    return $presses;
  }

  protected function fillcache() {
    foreach ($this->keyPads['directional'] as $startButton => $startPos) {
      foreach ($this->keyPads['directional'] as $endButton => $endPos) {
        if ($startButton === $endButton) {
          $this->cache->put(['path', $startButton, $endButton], ['A']);
          $this->cache->put(['length', $startButton, $endButton, 1 /* depth */], 1);
          continue;
        }
        $paths = $this->getPaths($startPos, $endPos, 'directional');
        usort($paths, fn ($a, $b) => strlen($a) - strlen($b));
        $shortestPaths = [array_shift($paths)];
        $len = strlen($shortestPaths[0]);
        $path = array_shift($paths);
        while ($path && strlen($path) <= $len) {
          $shortestPaths[] = $path;
          $path = array_shift($paths);
        }
        $this->cache->put(['path', $startButton, $endButton], $shortestPaths);
        $this->cache->put(['length', $startButton, $endButton, 0 /* depth */], $len);
      }
    }
  }
}
