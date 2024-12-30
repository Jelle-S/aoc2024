<?php

namespace Jelle_S\AOC\AOC2024\Day24;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $input = trim(file_get_contents($this->input));
    list($valuesDescriptions, $gateDescriptions) = explode("\n\n", $input);
    $values = [];
    foreach (explode("\n", $valuesDescriptions) as $v) {
      list($key, $value) = explode(': ', $v);
      $values[$key] = intval($value);
      // For the initial values, just "and" the gate with itself.
    }

    $gates = [];
    $dependencies = [];
    foreach (explode("\n", $gateDescriptions) as $gateDescription) {
      list($in1, $operator, $in2, , $output) = explode(' ', $gateDescription);
      $gates[$output] = [$in1, $in2, $operator, $output];
    }

    $values = array_filter($this->resolveKahns($gates, $values), fn($k) => substr($k, 0, 1) === 'z', ARRAY_FILTER_USE_KEY);
    krsort($values);
    return bindec(implode('', array_map('strval', $values)));
  }

  protected function resolveKahns(array $gates, array $values) {
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
    foreach (array_keys($values) as $gate) {
      $q->push($gate);
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

    return $values;
  }
}
