<?php

namespace Jelle_S\AOC\AOC2024\Day24;

class Puzzle2 extends Puzzle1 {

  public function solve() {
    $result = 0;

    $input = trim(file_get_contents($this->input));
    list($valuesDescriptions, $gateDescriptions) = explode("\n\n", $input);
    $xBin = $yBin = '';
    $inputValues = [];
    foreach (explode("\n", $valuesDescriptions) as $v) {
      list($key, $value) = explode(': ', $v);
      $inputValues[$key] = intval($value);
      switch(substr($key, 0, 1)) {
        case 'x':
          $xBin .= $value;
          break;
        case 'y':
          $yBin .= $value;
          break;
      }
    }

    $gates = [];
    $dependencies = [];
    foreach (explode("\n", $gateDescriptions) as $gateDescription) {
      list($in1, $operator, $in2, , $output) = explode(' ', $gateDescription);
      $gates[$output] = [$in1, $in2, $operator, $output];
    }

    for ($i = 0; $i < 44; $i++) {
      $inputValues['x' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 0;
      $inputValues['y' . str_pad($i, 2, '0', STR_PAD_LEFT)] = 1;
    }
    list($values, $paths) = $this->resolveKahns($gates, $inputValues);
    $values = array_filter($values, fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
    krsort($values);
    $endgates = array_keys($values);

    $real = implode('', array_map('strval', $values));
    $expected = bindec($xBin) + bindec($yBin);
    $mismatch = bindec($real) ^ $expected;

    $pairUsage = new \Ds\Set();
    foreach ($this->bitPositionsInMask($mismatch) as $position) {
      $pairUsage = $pairUsage->merge($paths[$endgates[$position]]);
    }

    arsort($pairUsage);
    foreach ($pairUsage as $pair) {
      
    }

    return count($pairUsage);
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

          $valuePaths[$output] = $valuePaths[$current]->copy();
          $pair = [$in1, $in2];
          sort($pair);
          $valuePaths[$output]->add($pair);

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
