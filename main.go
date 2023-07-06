package main

import (
	"bytes"
	"fmt"
	"os/exec"
	"runtime"
	"time"
)

func main() {
	runtime.GOMAXPROCS(1)
	start := time.Now()
	nchars := 0
	cmd := "./new"
	i := 0

	for time.Since(start).Seconds() < 1 {
		nchars += shellout(cmd)
		i++
	}

	elapsed := time.Since(start).Seconds()
	fmt.Printf("GO SHELL: Ran %d times in %.6f seconds.\n", i, elapsed)
}

func shellout(cmd string) int {
	command := exec.Command(cmd)

	var stdout bytes.Buffer
	var stderr bytes.Buffer
	command.Stdout = &stdout
	command.Stderr = &stderr

	err := command.Run()
	if err != nil {
		fmt.Println(err)
		return 0
	}

	return len(stdout.String())
}
