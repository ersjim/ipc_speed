#!/bin/bash

gcc -o new new.c -O3 -g0 -march=native

php ./shell.php

echo "run node new.js in another window and press enter"
read

php ./curl.php

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
