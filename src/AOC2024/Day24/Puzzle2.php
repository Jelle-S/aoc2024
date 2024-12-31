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
    $swapped = new \Ds\Set();
    while (true) {
      list($values, $paths) = $this->resolveKahns($gates, $inputValues);
      $values = array_filter($values, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
      //$paths = array_filter($paths, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
      krsort($values);
      krsort($paths);
      var_dump($paths['z00'],$paths['z01'], $paths['njb'], $paths['tkb']); exit;
      $wrongBit = $this->getLeastSignificantWrongBit($values, $expected);
      if ($wrongBit === -1) {
        break;
      }
      $validPairs = new \Ds\Set();
      for ($i = 0; $i < $wrongBit; $i++) {
        $validPairs = $validPairs->merge($paths['z' . str_pad($i, 2, '0', STR_PAD_LEFT)]);
      }
      $invalidPairs = $paths['z' . str_pad($wrongBit, 2, '0', STR_PAD_LEFT)];
      foreach ($invalidPairs->diff($validPairs) as $invalidPair) {
        $gate1 = $gatesByPair->get($invalidPair);
        foreach ($gates as $gate2) {
          if ($gate1 === $gate2) {
            continue;
          }
          $oldgates = $gates;
          $gates[$gate1[3]] = $gate2;
          $gates[$gate1[3]][3] = $gate1[3];
          $gates[$gate2[3]] = $gate1;
          $gates[$gate2[3]][3] = $gate2[3];
          list($newValues, $newPaths) = $this->resolveKahns($gates, $inputValues);
          $newValues = array_filter($newValues, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
          $newPaths = array_filter($newPaths, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
          krsort($newValues);
          $newWrongBit = $this->getLeastSignificantWrongBit($newValues, $expected);
          if (($newWrongBit <= $wrongBit && $newWrongBit !== -1) || count($newValues) !== strlen(decbin($expected))) {
            $gates = $oldgates;
            continue;
          }
          $pair1 = [$gates[$gate1[3]][0], $gates[$gate1[3]][1]];
          $pair2 = [$gates[$gate2[3]][0], $gates[$gate2[3]][1]];
          $swapped->add($gate1[3]);
          $swapped->add($gate2[3]);
          sort($pair1);
          sort($pair2);
          $gatesByPair->put($pair1, $gate1);
          $gatesByPair->put($pair2, $gate2);
          continue 2;
        }
      }
    }
    $swapped->sort();
    return implode(',', $swapped->toArray());
  }

  protected function resolveKahns(array $gates, array $values) {
    $valuePaths = [];
    $indegrees = array_fill_keys(array_keys($gates), 0);
    // Build a graph where we map all inputs to the operations they're part of.
    $graph = [];
    foreach ($gates as $output => $operation) {
      $indegrees[$output] += 2;
      list($in1, $in2) = $operation;
      $graph[$in1] ??= [];
      $graph[$in2] ??= [];
      $graph[$in1][] = $operation;
      $graph[$in2][] = $operation;
    }

    $q = new \Ds\Queue();
    foreach ($values as $gate => $value) {
      $q->push($gate);
      $valuePaths[$gate] = new \Ds\Set();
    }

    while(!$q->isEmpty()) {
      $current = $q->pop();

      // This gate as no gates that use its output as input.
      if (!array_key_exists($current, $graph)) {
        continue;
      }

      // Loop over all operations that use this (resolved) gate as input.
      foreach ($graph[$current] as $operation) {
        list ($in1, $in2, $operator, $output) = $operation;
        $indegrees[$output]--;
        // All the inputs of this gate are resolved, process it and add it to
        // the queue.
        if ($indegrees[$output] === 0) {
          $valuePaths[$output] ??= $valuePaths[$current]->copy();
          $pair = [$in1, $in2];
          sort($pair);
          $valuePaths[$output]->add(implode(' ', [...$pair, $operator, $output]));
          switch ($operator) {
            case 'AND':
              $values[$output] = $values[$in1] & $values[$in2];
              break;
            case 'OR':
              $values[$output] = $values[$in1] | $values[$in2];
              break;

            case 'XOR':
              $values[$output] = $values[$in1] ^ $values[$in2];
              break;
          }
          $q->push($output);
        }
      }
    }

    return [$values, $valuePaths];
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
