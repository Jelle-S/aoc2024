<?php

namespace Jelle_S\AOC\AOC2024\Day21;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  protected array $keyPads;
  protected \Ds\Map $cache;

  public function __construct(protected string $input) {
    $this->keyPads = [];
    $this->keyPads['numeric'] = [
      '7' => [0, 0], '8' => [0, 1], '9' => [0, 2],
      '4' => [1, 0], '5' => [1, 1], '6' => [1, 2],
      '1' => [2, 0], '2' => [2, 1], '3' => [2, 2],
                     '0' => [3, 1], 'A' => [3, 2],
    ];

    $this->keyPads['directional'] = [
                     '^' => [0, 1], 'A' => [0, 2],
      '<' => [1, 0], 'v' => [1, 1], '>' => [1, 2],
    ];

    $this->cache = new \Ds\Map();
  }

  public function solve() {
    $result = 0;
    $complexities = [];

    $h = fopen($this->input, 'r');
    while (($code = fgets($h)) !== false) {
      $code = trim($code);
      $complexities[] = $this->getComplexity($code);
    }
    fclose($h);

    $result = array_sum($complexities);

    return $result;
  }

  protected function getComplexity(string $code) {
    $buttons = str_split($code);
    $codeVal = intval(substr($code, 0, -1));
    $presses = $this->getButtonPresses($buttons, ['numeric', 'directional', 'directional']);
    return $codeVal * strlen($presses);

  }

  protected function getButtonPresses($buttons, $padTypes) {
    if (!$padTypes) {
      return implode('', $buttons);
    }
    $padType = array_shift($padTypes);
    $pos = $this->keyPads[$padType]['A'];

    $presses = '';
    foreach ($buttons as $button) {
      $paths = $this->getPaths($pos, $this->keyPads[$padType][$button], $padType);
      $pathPresses = [];
      foreach ($paths as $path) {
        $pathPresses[] = $this->getButtonPresses(str_split($path), $padTypes);
      }
      usort($pathPresses, fn ($a, $b) => strlen($a) - strlen($b));
      $presses .= reset($pathPresses);
      $pos = $this->keyPads[$padType][$button];
    }
    return $presses;
  }

  protected function getPaths($start, $end, $padType) {
    list($sr, $sc) = $start;
    list($er, $ec) = $end;
    if ($this->cache->hasKey([$sr, $sc, $er, $ec, $padType])) {
      return $this->cache->get([$sr, $sc, $er, $ec, $padType]);
    }

    $grid = [];
    foreach ($this->keyPads[$padType] as $val => $coords) {
      $grid[$coords[0]] ??= [];
      $grid[$coords[0]][$coords[1]] = $val;
    }
    $q = new \Ds\Queue();
    $q->push([$sr, $sc, '', 0]);
    $visited = new \Ds\Set();
    $visited->add([$sr, $sc]);

    $paths = [];
    $directions = [[0, 1, '>'], [1, 0, 'v'], [-1, 0, '^'], [0, -1, '<']];

    while (!$q->isEmpty()) {
      list($r, $c, $path) = $q->pop();
      if ($r === $er && $c === $ec) {
        // Confirm with A.
        $paths[] = $path . 'A';
      }
      $visited->add([$r, $c]);
      foreach ($directions as $direction) {
        list ($dr, $dc, $button) = $direction;
        $nr = $r + $dr;
        $nc = $c + $dc;
        if (!$visited->contains([$nr, $nc]) && array_key_exists($nr, $grid) && array_key_exists($nc, $grid[$nr])) {
          $q->push([$nr, $nc, $path . $button]);
        }
      }
    }

    $this->cache->put([$sr, $sc, $er, $ec, $padType], $paths);
    return $paths;
  }
}
