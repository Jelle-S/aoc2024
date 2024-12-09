<?php

namespace Jelle_S\AOC\AOC2024\Day09;

use Jelle_S\AOC\Contracts\PuzzleInterface;

class Puzzle1 implements PuzzleInterface {

  public function __construct(protected string $input) {
  }

  public function solve() {
    $result = 0;

    $h = fopen($this->input, 'r');

    while (($line = fgets($h)) !== false) {
      $disk = array_map('intval', str_split(trim($line)));
    }
    fclose($h);

    $data = $space = [];

    foreach ($disk as $i => $len) {
      if ($i % 2) {
        $space[] = $len;
        continue;
      }
      $data[] = $len;
    }

    $result = $this->defraggedChecksum($data, $space);

    return $result;
  }

  protected function defraggedChecksum($data, $space) {
    $checksum = 0;

    // First file has file id 0 -> irrelevant.
    $fids = array_keys($data);
    array_shift($fids);
    $position = array_shift($data);
    $fill_space = false;
    while($data) {
      $fill_space = !$fill_space;
      if ($fill_space) {
        $fid = end($fids);
        $len = end($data);
        $spaces = array_shift($space);
        for ($i = 0; $i < $spaces; $i++) {
          $checksum += $position * $fid;
          $position++;
          $len--;
          if ($len === 0) {
            array_pop($data);
            array_pop($fids);
            $fid = end($fids);
            $len = end($data);
          }
        }
        $data[count($data) - 1] = $len;
        continue;
      }
      $len = array_shift($data);
      $fid = array_shift($fids);
      for ($i = 0; $i < $len; $i++) {
        $checksum += $position * $fid;
        $position++;
      }
    }

    return $checksum;
  }
}
