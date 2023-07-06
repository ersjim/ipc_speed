#include <stdio.h>
#include <string.h>
#include <time.h>
#include <spawn.h>
#include <sys/wait.h>
#include <unistd.h>
#include <errno.h>
#include <stdlib.h>

#define CMD "./new"
#define MAX_BUF 1024

extern char **environ;

int shellout(char *cmd);

int main() {
    struct timespec start, end;
    int i = 0, total_chars = 0;

    clock_gettime(CLOCK_MONOTONIC, &start);

    while (1) {
        int chars = shellout(CMD);
        if (chars < 0) {
            printf("Failed to run command '%s'\n", CMD);
            perror("shellout");
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
    printf("C SHELL: Ran %d times in %f seconds.\n", i, cpu_time_used);

    return 0;
}

int shellout(char *cmd) {
    int pipefd[2];
    if (pipe(pipefd) == -1) {
        return -1;
    }

    pid_t pid;
    posix_spawn_file_actions_t action;
    posix_spawn_file_actions_init(&action);
    posix_spawn_file_actions_addclose(&action, pipefd[0]);  // close read end
    posix_spawn_file_actions_adddup2(&action, pipefd[1], STDOUT_FILENO);  // duplicate write end to stdout

    char *argv[] = {cmd, NULL};
    if (posix_spawn(&pid, cmd, &action, NULL, argv, environ) != 0) {
        return -1;
    }

    close(pipefd[1]);  // close write end

    char buffer[MAX_BUF];
    FILE *stream = fdopen(pipefd[0], "r");  // read from the reading end
    if (!stream) {
        return -1;
    }

    int total_chars = 0;
    while (fgets(buffer, sizeof(buffer), stream) != NULL) {
        total_chars += strlen(buffer);
    }

    fclose(stream);

    int status;
    waitpid(pid, &status, 0);

    return total_chars;
}
