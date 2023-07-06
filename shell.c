#include <stdio.h>
#include <string.h>
#include <time.h>

#define CMD "./new"
#define MAX_BUF 1024

int shellout(char *cmd);

int main() {
    struct timespec start, end;
    int i = 0, total_chars = 0;

    clock_gettime(CLOCK_MONOTONIC, &start);

    while (1) {
        int chars = shellout(CMD);
        if (chars < 0) {
            printf("Failed to run command '%s'\n", CMD);
            return 127;
        }
        total_chars += chars;
        i++;

        clock_gettime(CLOCK_MONOTONIC, &end);
        if ((end.tv_sec - start.tv_sec) + (end.tv_nsec - start.tv_nsec) / 1E9 >= 1.0) {
            break;
        }
    }

    double cpu_time_used = (end.tv_sec - start.tv_sec) + (end.tv_nsec - start.tv_nsec) / 1E9;
    printf("%d chars written. Ran %d times in %f seconds.\n", total_chars, i, cpu_time_used);

    return 0;
}

int shellout(char *cmd) {
    char buffer[MAX_BUF];
    FILE *pipe = popen(cmd, "r");
    if (!pipe) {
        return -1;
    }

    int total_chars = 0;
    while (fgets(buffer, sizeof(buffer), pipe) != NULL) {
        total_chars += strlen(buffer);
    }

    pclose(pipe);
    return total_chars;
}
