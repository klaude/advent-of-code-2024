<?php

// https://adventofcode.com/2024/day/4

declare(strict_types=1);

/**
 * Possible directions in which to search the letters grid
 */
enum Direction
{
    case Up;
    case UpRight;
    case Right;
    case DownRight;
    case Down;
    case DownLeft;
    case Left;
    case UpLeft;
}

function findWord(array $word, int $x, int $y, Direction $direction): bool {
    // We hit the end of the word to search for. We found it!
    if (count($word) == 0) {
        return true;
    }

    // Check to see if we're outside the input grid
    if ($x < 0) {
        return false;
    }

    if ($x > MAX_X) {
        return false;
    }

    if ($y < 0) {
        return false;
    }

    if ($y > MAX_Y) {
        return false;
    }

    $letter = array_shift($word);

    if (LETTERS[$y][$x] !== $letter) {
        return false;
    }

    // We got the expected letter. Look for the next one.
    $nextCoordinates = nextCoordinates($x, $y, $direction);
    return findWord($word, $nextCoordinates[0], $nextCoordinates[1], $direction);
}

function nextCoordinates(int $x, int $y, Direction $direction): array {
    if (str_contains($direction->name, 'Up')) {
        $y--;
    }

    if (str_contains($direction->name, 'Down')) {
        $y++;
    }

    if (str_contains($direction->name, 'Left')) {
        $x--;
    }

    if (str_contains($direction->name, 'Right')) {
        $x++;
    }

    return [$x, $y];
}

// Read the input into a nested array
$input = trim(file_get_contents(__DIR__ . '/input'));

$letters = [];
$inputLines = explode("\n", $input);
foreach ($inputLines as $line) {
    $letters[] = str_split($line);
}

// Set a few constants for easy reference later
define('LETTERS', $letters);
define('MAX_X', count($letters[0]) - 1);
define('MAX_Y', count($letters) - 1);

// Search through every (x,y) coordinate for the word
$count = 0;
foreach (LETTERS as $y => $line) {
    foreach ($line as $x => $letter) {
        // Part 1: Look for the string "XMAS" in any direction
        // foreach (Direction::cases() as $direction) {            
        //     if (findWord(['X', 'M', 'A', 'S'], $x, $y, $direction)) {
        //         $count++;
        //     }
        // }

        // Part 2: Look for X'd "MAS" patterns around "A". This ditches all of
        // my fun helper functions, but I couldn't get those quight right, so
        // here's a quick hacky solution. :( Scroll down for what could have
        // been.
        if ($x === 0) {
            continue;
        }

        if ($y === 0) {
            continue;
        }

        if ($x === MAX_X) {
            continue;
        }

        if ($y === MAX_Y) {
            continue;
        }

        if ($letter !== 'A') {
            continue;
        }

        // M.M
        // .A.
        // S.S
        if (
            LETTERS[$y - 1][$x - 1] === 'M' 
            && LETTERS[$y - 1][$x + 1] === 'M'
            && LETTERS[$y + 1][$x + 1] === 'S'
            && LETTERS[$y + 1][$x - 1] === 'S'
        ) {
            $count++;
        }

        // S.M
        // .A.
        // S.M
        if (
            LETTERS[$y - 1][$x - 1] === 'S' 
            && LETTERS[$y - 1][$x + 1] === 'M'
            && LETTERS[$y + 1][$x + 1] === 'M'
            && LETTERS[$y + 1][$x - 1] === 'S'
        ) {
            $count++;
        }

        // S.S
        // .A.
        // M.M
        if (
            LETTERS[$y - 1][$x - 1] === 'S' 
            && LETTERS[$y - 1][$x + 1] === 'S'
            && LETTERS[$y + 1][$x + 1] === 'M'
            && LETTERS[$y + 1][$x - 1] === 'M'
        ) {
            $count++;
        }

        // M.S
        // .A.
        // M.S
        if (
            LETTERS[$y - 1][$x - 1] === 'M' 
            && LETTERS[$y - 1][$x + 1] === 'S'
            && LETTERS[$y + 1][$x + 1] === 'S'
            && LETTERS[$y + 1][$x - 1] === 'M'
        ) {
            $count++;
        }

        // Part 2: look for the string "MAS" in an X pattern. Limit
        // searching to diagonal directions. If "MAS" is found then search
        // for its cross equivalent depending on the direction.
        //
        // This ended up not working, so I went with the less fun brute force
        // above.
        // $directions = [Direction::UpRight, Direction::DownRight];
        //
        // foreach ($directions as $direction) {
        //     if (!findWord(['M', 'A', 'S'], $x, $y, $direction)) {
        //         continue;
        //     }
        //
        //     // Build a list of potential crosses
        //     $crossAttempts = match($direction) {
        //         Direction::UpRight => [
        //             [$x, $y - 2, Direction::DownRight],
        //             [$x + 2, $y, Direction::UpLeft],
        //         ],
        //         Direction::DownRight => [
        //             [$x + 2, $y, Direction::DownLeft],
        //             [$x, $y + 2, Direction::UpLeft],
        //         ],
        //     };
        //
        //     foreach ($crossAttempts as list($newX, $newY, $newDirection)) {
        //         if (findWord(['M', 'A', 'S'], $newX, $newY, $newDirection)) {
        //             $count++;
        //         }
        //     }
        // }
    }
}

echo "{$count}\n";