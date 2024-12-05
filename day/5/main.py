#!/usr/bin/env python3

# https://adventofcode.com/2024/day/5

# Determine if pages are sorted by the given rules. If the pages aren't sorted
# then also return the index of the first unsorted page to help sorting in part 2.
def is_sorted(rules: list[list[str]], pages: list[str]) -> tuple[bool, int]:
    for i, page in enumerate(pages):
        # If we got to the last page in the list then all the others are
        # verified and the list is sorted.
        if i == len(pages) - 1:
            return True, 0

        # Filter rules again to those that only pertain to the current page
        related_rules = []
        for rule in filtered_rules:
            if rule[0] == page or rule[1] == page:
                related_rules.append(rule)

        preceeding_pages = pages[:i]
        succeeding_pages = pages[i + 1:]

        for rule in related_rules:
            # It's not sorted if there pages before the current page but have a
            # rule saying it goes after the current page.
            if rule[0] == page and rule[1] in preceeding_pages:
                return False, i

            # It's not sorted if there pages after the current page but have a
            # rule saying it goes before the current page.
            if rule[0] in succeeding_pages and rule[1] == page:
                return False, i

# Sort pages by the given rules
#
# I'm sure there are more efficient ways to do this. I haven't had to write a
# sorting algorithm since university. :P
def sort(rules: list[list[str]], pages: list[str], start_idx: int) -> list[str]:
    for i in range(start_idx + 1, len(pages)):
        # Swap out the pages from the current index to the end of the page
        # list, checking to see if it's sorted along the way.
        temp = pages[start_idx]
        pages[start_idx] = pages[i]
        pages[i] = temp

        # Check if the pages are sorted. If so, great! Otherwise, if the index
        # of the first unsorted page is greater than the current index then
        # pages are now sorted at index i, so continue sorting from the next
        # page out of order.
        pages_are_sorted, unsorted_idx = is_sorted(rules, pages)
        if pages_are_sorted:
            return pages
        elif unsorted_idx > start_idx:
            return sort(rules, pages, unsorted_idx)

        # Swap values back for the next iteration
        temp = pages[i]
        pages[i] = pages[start_idx]
        pages[start_idx] = temp

    raise Exception('sorting error!')


with open('./input') as f:
    lines = f.read().splitlines()

all_rules = []
page_updates = []

readingUpdates = False
for line in lines:
    # Rules are separated from update lists by a blank line
    if line == '':
        readingUpdates = True
        continue

    if readingUpdates:
        page_updates.append(line.split(','))
    else:
        all_rules.append(line.split('|'))

middle_digit_sum = 0
for pages in page_updates:
    # Filter rules down to rules that only applies to the given pages
    filtered_rules = [rule for rule in all_rules if rule[0] in pages and rule[1] in pages]

    pages_are_sorted, unsorted_idx = is_sorted(filtered_rules, pages)

    # Part 1: Look for updates that are sorted
    # if pages_are_sorted:
    #     middle_digit_sum += int(pages[len(pages) // 2])

    # Part 2: Sort updates that aren't sorted
    if not pages_are_sorted:
        sorted_pages = sort(filtered_rules, pages, unsorted_idx)
        middle_digit_sum += int(sorted_pages[len(sorted_pages) // 2])

print(middle_digit_sum)
