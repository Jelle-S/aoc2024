<?php

namespace Jelle_S\AOC\AOC2024\Day24;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $input = trim(file_get_contents($this->input));
    list($valuesDescriptions, $gateDescriptions) = explode("\n\n", $input);
    $xBin = $yBin = [];
    $inputValues = [];
    foreach (explode("\n", $valuesDescriptions) as $v) {
      list($key, $value) = explode(': ', $v);
      $inputValues[$key] = intval($value);
      switch(substr($key, 0, 1)) {
        case 'x':
          $xBin[$key] = $value;
          break;
        case 'y':
          $yBin[$key] = $value;
          break;
      }
    }

    krsort($xBin);
    krsort($yBin);

    $xBin = implode($xBin);
    $yBin = implode($yBin);

    $gates = [];
    $gatesByPair = new \Ds\Map();

    foreach (explode("\n", $gateDescriptions) as $gateDescription) {
      list($in1, $operator, $in2, , $output) = explode(' ', $gateDescription);
      $gates[$output] = [$in1, $in2, $operator, $output];
      $pair = [$in1, $in2];
      sort($pair);
      $gatesByPair->put($pair, [$in1, $in2, $operator, $output]);
    }

    $expected = bindec($xBin) + bindec($yBin);

    $values = $this->resolveKahns($gates, $inputValues);
    $values = array_filter($values, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
    krsort($values);
    $debugPaths = array_filter($paths, fn($k) => in_array($k, ['z00', 'z01', 'z02', 'njb', 'tkb']), ARRAY_FILTER_USE_KEY);
    $toCheck = $this->getLeastSignificantWrongBit($values, $expected);

    // Find the gate that is 'wrong'.
    $this->findMistake($gates['z' . str_pad($toCheck, 2, '0', STR_PAD_LEFT)], $toCheck);
    return 0;
  }

  protected function isValid($gate, $bitNum) {
    if ($bitNum === 0) {
      $pair = [$gate[0], $gate[1]];
      sort($pair);
      $op = $gate[2];
      return $pair === ['x00', 'y00'] && $op === 'XOR';
    }

    // Uhm... verify the rest somehow. I need to figure this out still.
  }

  protected function getLeastSignificantWrongBit($values, $expected) {
    $real = implode('', array_map('strval', $values));
    $mismatch = bindec($real) ^ $expected;
    return $mismatch > 0 ? min($this->bitPositionsInMask($mismatch)) : -1;
  }

  protected function bitPositionsInMask($mask) {
    $positions = [];
    $len = strlen(decbin($mask));
    for($i = 0; $i < $len; $i++) {
      $bit = 1 << $i;
      if ($mask & $bit) {
        $positions[] = $i;
      }
    }

    return $positions;
  }

  protected function bitwiseNot($mask) {
    $len = strlen(decbin($mask));
    // "All 1" mask to fix PHP's weird "bitwise not" behavior.
    $fixMask = (1 << $len) - 1;

    return ~$mask & $fixMask;
  }
}
