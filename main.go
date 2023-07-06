package main

import (
	"bytes"
	"fmt"
	"os/exec"
	"time"
)

func main() {
	start := time.Now()
	var output bytes.Buffer
	cmd := "./new"
	i := 0

	for time.Since(start).Seconds() < 1 {
		stdout, _, _ := shellout(cmd)
		output.WriteString(stdout)
		i++
	}

	elapsed := time.Since(start).Seconds()
	fmt.Printf("GO SHELL: Ran %d times in %.6f seconds.", i, elapsed)
}

func shellout(cmd string) (string, string, int) {
	command := exec.Command(cmd)

	var stdout bytes.Buffer
	var stderr bytes.Buffer
	command.Stdout = &stdout
	command.Stderr = &stderr

	err := command.Run()
	if err != nil {
		return "", fmt.Sprintf("command '%s' does not exist", cmd), 127
	}

	return stdout.String(), stderr.String(), command.ProcessState.ExitCode()
}
