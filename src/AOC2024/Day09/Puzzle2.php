<?php

namespace Jelle_S\AOC\AOC2024\Day09;

class Puzzle2 extends Puzzle1 {

  #[\Override]
  protected function defraggedChecksum($data, $space) {
    $fid = count($data) - 1;
    $checksum = 0;
    $space_offsets = array_fill(0, count($space), 0);
    while ($fid >= 0) {
      $len = $data[$fid];
      $position = 0;
      for ($i = 0; $i < $fid; $i++) {
        $position += $data[$i];
        $position += $space_offsets[$i];
        if ($len <= $space[$i]) {
          for ($j = 0; $j < $len; $j++) {
            $checksum += $position * $fid;
            $position++;
          }
          $space[$i] -= $len;
          $space_offsets[$i] += $len;
          $fid--;
          continue 2;
        }
        $position += $space[$i];
      }
      for ($k = 0; $k < $len; $k++) {
        $checksum += $position * $fid;
        $position++;
      }
      $fid--;
    }

    return $checksum;
  }
}
