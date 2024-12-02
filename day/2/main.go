package main

// https://adventofcode.com/2024/day/2

import (
	"bufio"
	"fmt"
	"math"
	"os"
	"strconv"
	"strings"
)

func isSafe(levels []int) bool {
	// Levels must be all increasing or decreasing. Determine the order by
	// comparing the first two pairs
	isIncreasing := levels[0] < levels[1]

	// Look through the levels for things that are unsafe
	for i := range levels {
		// We hit the end of the range of levels
		if i == len(levels)-1 {
			return true
		}

		// We were increasing then found a decrease
		if isIncreasing && levels[i] > levels[i+1] {
			return false
		}

		// We were decreasing then found an increase
		if !isIncreasing && levels[i] < levels[i+1] {
			return false
		}

		// Found equal levels
		if levels[i] == levels[i+1] {
			return false
		}

		// Levels increased or decreased by a value greater than 3
		if math.Abs(float64(levels[i])-float64(levels[i+1])) > 3 {
			return false
		}
	}

	// If we got here then levels is empty, which I guess is safe?
	return true
}

func main() {
	safeCount := 0

	f, err := os.Open("./input")
	if err != nil {
		panic(err)
	}
	defer f.Close()

	scanner := bufio.NewScanner(f)
	for scanner.Scan() {
		line := scanner.Text()
		parts := strings.Split(line, " ")
		levels := make([]int, len(parts))

		for i := range parts {
			level, err := strconv.Atoi(parts[i])
			if err != nil {
				panic(err)
			}
			levels[i] = level
		}

		// Pass 1: Check for safe levels
		if isSafe(levels) {
			safeCount++
			continue
		}

		// Pass 2: Check for safe levels with one of the levels removed, once
		// for each level in levels
		pass2Safe := false
		for i := range levels {
			if pass2Safe {
				continue
			}

			filteredLevels := make([]int, 0)
			for j := range levels {
				if i == j {
					continue
				}

				filteredLevels = append(filteredLevels, levels[j])
			}

			if isSafe(filteredLevels) {
				safeCount++
				pass2Safe = true
				continue
			}
		}
	}

	fmt.Println(safeCount)
}
