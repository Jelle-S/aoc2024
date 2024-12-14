<?php

namespace Jelle_S\AOC\AOC2024\Day14;

class Puzzle2 extends Puzzle1 {

    public function solve() {
        $minSafetyFactor = PHP_INT_MAX;
        $iteration = null;
        for ($i = 0; $i < $this->width * $this->height; $i++) {
            $this->time = $i;
            $h = fopen($this->input, 'r');
            $quadrants = [0, 0, 0, 0];
            while (($line = fgets($h)) !== false) {
                $line = trim($line);
                $matches = [];
                preg_match_all('/-?\d+/', $line, $matches);
                $quadrant = $this->getEndQuadrant(
                        [intval($matches[0][0]), intval($matches[0][1])],
                        [intval($matches[0][2]), intval($matches[0][3])]
                );
                if ($quadrant === false) {
                    continue;
                }
                $quadrants[$quadrant]++;
            }
            fclose($h);
            $result = array_product($quadrants);
            if ($result < $minSafetyFactor) {
                $minSafetyFactor = $result;
                $iteration = $i;
            }
        }

        return $iteration;
    }
}
