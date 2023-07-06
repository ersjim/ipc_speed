#!/bin/bash

echo "compiling"
gcc -o new new.c -O3 -g0 -march=native
gcc -o shell shell.c -fsanitize=address -fsanitize=null -fsanitize=undefined -Wall -Wextra -g3 -Og
go build main.go

echo "testing php shell version"
php ./shell.php

echo
echo "testing go shell version"
./main

echo
echo "testing c shell version"
./shell

echo "cleaning up"
rm -f new main shell

echo
echo
echo ========================
echo "run 'node new.js' in another window and press enter"
echo ========================
read

echo "testing curl version"
php ./curl.php
echo
echo "done"
echo --------
echo

# # Start the timer
# start=$(date +%s.%N)
# 
# # Run the file 1000 times
# for ((i=0; i<1000; i++)); do
#     # Replace `your-file` with the actual file name or command you want to execute
#     ./new
# done
# 
# # Calculate the elapsed time
# end=$(date +%s.%N)
# elapsed=$(echo "$end - $start" | bc)
# 
# echo "Elapsed time: $elapsed seconds"
