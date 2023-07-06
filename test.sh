#!/bin/bash

echo ========================
echo "run 'node new.js' in another window and press enter"
echo ========================
read

echo "compiling"
gcc -o new new.c -O3 -g0 -march=native
gcc -o shell shell.c -g0 -O3 -march=native
go build main.go

echo
php ./shell.php

echo
./main

echo
./shell

echo
php ./curl.php

echo
echo "cleaning up"
rm -f new main shell
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
