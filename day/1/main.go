package main

// https://adventofcode.com/2024/day/1

import (
	"bufio"
	"fmt"
	"os"
	"sort"
	"strconv"
	"strings"
)

func main() {
	leftColumn := make([]int, 0)
	rightColumn := make([]int, 0)
	totalSum := 0
	totalSimilarityScore := 0

	f, err := os.Open("./input")
	if err != nil {
		panic(err)
	}
	defer f.Close()

	scanner := bufio.NewScanner(f)
	for scanner.Scan() {
		line := scanner.Text()
		parts := strings.Split(line, "   ")

		left, err := strconv.Atoi(parts[0])
		if err != nil {
			panic(err)
		}

		right, err := strconv.Atoi(parts[1])
		if err != nil {
			panic(err)
		}

		leftColumn = append(leftColumn, left)
		rightColumn = append(rightColumn, right)
	}

	sort.Ints(leftColumn)
	sort.Ints(rightColumn)

	for i := 0; i < len(leftColumn); i++ {
		a := leftColumn[i]
		b := rightColumn[i]
		sum := 0
		similarityScore := 0

		if a > b {
			sum = a - b
		}

		if b > a {
			sum = b - a
		}

		rightOccurences := 0
		for j := 0; j < len(rightColumn); j++ {
			if rightColumn[j] == a {
				rightOccurences++
			}
		}
		similarityScore = a * rightOccurences

		totalSum = totalSum + sum
		totalSimilarityScore = totalSimilarityScore + similarityScore
	}

	fmt.Println(totalSum)
	fmt.Println(totalSimilarityScore)
}
