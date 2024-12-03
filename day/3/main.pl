#!/usr/bin/env perl

# https://adventofcode.com/2024/day/3

use strict;
use warnings;

# Read the input
my $input;
open(FH, '<', './input') or die $!;
while(<FH>) {
  $input .= $_;
}
close(FH);

# Part 1: Pull the mul() matches out of the input
# my @matches = $input =~ /mul\([0-9]{1,3},[0-9]{1,3}\)/g;

# Part 2: Pull "don't()", "do()", and mul() matches out of the input
my @matches = $input =~ /(don't\(\)|do\(\)|mul\([0-9]{1,3},[0-9]{1,3}\))/g;

# Pull the multiples out of the mul() instructions, multiply them, and add them
# to the total
my $do = 1;
my $total = 0;
foreach my $match (@matches) {
  if ($match eq "don't()") {
    $do = 0;
    next;
  }

  if ($match eq "do()") {
    $do = 1;
    next;
  }
  
  if ($do) {
    my @multipliers = $match =~ /([0-9]{1,3}),([0-9]{1,3})/;
    $total += $multipliers[0] * $multipliers[1];
  }
}

print $total . "\n";
