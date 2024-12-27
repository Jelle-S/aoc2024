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

    $biggestLan = $this->getBiggestLan($connections);

    sort($biggestLan);

    return implode(',', $biggestLan);
  }

  protected function getBiggestLan($connections) {
    $networks = new \Ds\Set();
    foreach ($connections as $pc => $linked) {
      $network = [$pc, ...$linked];
      sort($network);
      $networks->add($network);
    }

    $networkWithBiggestLan = null;
    $biggestLanMask = 0;
    $size = 0;
    foreach ($networks as $network) {
      $networkPcsByMask = array_combine(array_map(fn ($v) => 1 << $v, range(0, count($network) - 1)), $network);
      foreach($this->getAllMasks(count($networkPcsByMask), $size) as $possibleLanMask) {
        if (substr_count(decbin($possibleLanMask), '1') <= $size) {
          continue;
        }
        if (!$this->isValidLan($possibleLanMask, $networkPcsByMask, $connections)) {
          continue;
        }
        $size = substr_count(decbin($possibleLanMask), '1');
        $biggestLanMask = $possibleLanMask;
        $networkWithBiggestLan = $networkPcsByMask;
      }
    }

    $pcMasks = $this->bitsInMask($biggestLanMask);

    return array_intersect_key($networkWithBiggestLan, array_combine($pcMasks, $pcMasks));
  }

  protected function getAllMasks($length, $size) {
    $masks = range(bindec(str_repeat('1', $size + 1)), bindec(str_repeat('1', $length)));
    usort($masks, function ($a, $b) {
      $bina = decbin($a);
      $binb = decbin($b);
      $result = substr_count($binb, '1') - substr_count($bina, '1');
      return $result === 0 ? strlen($bina) - strlen($binb) : $result;
    });
    return array_filter($masks, function ($v) use ($size) { return substr_count(decbin($v), '1') > $size; });
  }

  protected function isValidLan($lanMask, $networkMasks, $connections) {
    foreach ($this->bitsInMask($lanMask) as $lanPcMask) {
      foreach ($this->bitsInMask($lanMask & $this->bitwiseNot($lanPcMask)) as $otherPcMask) {
        if (!in_array($networkMasks[$lanPcMask], $connections[$networkMasks[$otherPcMask]])) {
          return false;
        }
      }
    }

    return true;
  }

  protected function bitwiseNot($mask) {
    $len = strlen(decbin($mask));
    // "All 1" mask to fix PHP's weird "bitwise not" behavior.
    $fixMask = (1 << $len) - 1;

    return ~$mask & $fixMask;
  }

  protected function bitsInMask($mask) {
    $bits = [];
    $len = strlen(decbin($mask));
    for($i = 0; $i < $len; $i++) {
      $bit = 1 << $i;
      if ($mask & $bit) {
        $bits[] = $bit;
      }
    }

    return $bits;
  }
}
