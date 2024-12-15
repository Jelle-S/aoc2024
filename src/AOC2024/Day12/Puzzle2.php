<?php

namespace Jelle_S\AOC\AOC2024\Day12;

class Puzzle2 extends Puzzle1 {

    public function solve() {
        $result = 0;

        $h = fopen($this->input, 'r');

        while (($line = fgets($h)) !== false) {
            $line = trim($line);
            $this->grid[] = str_split($line);
        }
        fclose($h);

        $this->rows = count($this->grid);
        $this->cols = count($this->grid[0]);
        $polygons = $this->getPolygons();
        $result = array_sum(array_map(fn($p) => $this->countCorners($p) * $p->count(), $polygons));
        return $result;
    }

    protected function getPolygon(int $r, int $c, \Ds\Set &$visited) {
        $directions = [[0, 1], [1, 0], [-1, 0], [0, -1]];
        $char = $this->grid[$r][$c];
        $polygon = new \Ds\Set();
        $queue = new \Ds\Queue();
        $queue->push([$r, $c]);
        $visited->add([$r, $c]);

        while (!$queue->isEmpty()) {
            list($r, $c) = $queue->pop();
            $polygon->add([$r, $c]);
            foreach ($directions as $direction) {
                list($dr, $dc) = $direction;
                $nr = $r - $dr;
                $nc = $c - $dc;

                if ($nc < 0 || $nc >= $this->cols || $nr < 0 || $nr >= $this->rows) {
                    continue;
                }

                if ($this->grid[$nr][$nc] !== $char) {
                    continue;
                }

                if ($visited->contains([$nr, $nc])) {
                    continue;
                }

                $queue->push([$nr, $nc]);
                $visited->add([$nr, $nc]);
            }
        }

        return $polygon;
    }

    protected function countCorners(\Ds\Set $polygon): int {
        /**
         * By using half coordinates we can check each 'corner' of this
         * plant (pixel). There are a couple of options (assuming the
         * current plant type is A, current pixel/plant we're checking
         * is A1 and we're checking the top left corner of this pixel.
         * We can repeat the check for every corner of this pixel:
         * No matching neighbours (1 A in the 4 pixels including itself):
         * ----- -----
         * | B | | B |
         * ----- -----
         * ----- -----
         * | B | | A1|
         * ----- -----
         * -> We have 1 corner.
         *
         * 1 matching neighbour(2 As in the 4 pixels including itself):
         * ----- -----    ----- -----
         * | B | | A |    | A | | B |
         * ----- -----    ----- -----
         * ----- ----- or ----- -----
         * | B | | A1|    | B | | A1|
         * ----- -----    ----- -----
         * -> We have 0 corners if they're on the same row or column.
         * -> We have 2 corners if they're on a different row and
         * column.
         *
         * 2 matching neighbours(3 As in the 4 pixels including itself):
         * ----- -----
         * | B | | A |
         * ----- -----
         * ----- -----
         * | A | | A1|
         * ----- -----
         * -> We have 1 corner
         *
         * All neighbours match(4 As in the 4 pixels including itself):
         * ----- -----
         * | A | | A |
         * ----- -----
         * ----- -----
         * | A | | A1|
         * ----- -----
         *
         * -> We have 0 corners.
         *
         * Note: I use the term "matching" a lot here. In this case it means it
         * has to be part of the _same_ polygon. It can't just be the same char.
         *
         * Second note: We must only check each pixel corner once. The top left
         * corner of the pixel might be the bottom left of the pixel below it,
         * if it is also part of this polygon.
         */
        $corners = [[0.5, 0.5], [0.5, -0.5], [-0.5, 0.5], [-0.5, -0.5]];
        $count = 0;
        $corner_candidates = new \Ds\Set();

        foreach ($polygon as $pos) {
            list($r, $c) = $pos;
            foreach ($corners as $corner) {
                list($dr, $dc) = $corner;
                // Corner row, corner column;
                $cr = $r + $dr;
                $cc = $c + $dc;
                $corner_candidates->add([$cr, $cc]);
            }
        }

        foreach ($corner_candidates as $corner_candidate) {
            list($ccr, $ccc) = $corner_candidate;
            $matching = [];
            foreach ($corners as $corner) {
                list($dr, $dc) = $corner;
                $nr = intval($ccr + $dr);
                $nc = intval($ccc + $dc);
                if ($polygon->contains([$nr, $nc])) {
                    $matching[] = [$nr, $nc];
                }
            }
            switch (count($matching)) {
                case 4:
                    $count += 0;
                    break;
                case 3:
                case 1:
                    $count += 1;
                    break;
                case 2:
                    $count += $matching[0][0] === $matching[1][0] || $matching[0][1] === $matching[1][1] ? 0 : 2;
                    break;
            }
        }

        return $count;
    }
}
