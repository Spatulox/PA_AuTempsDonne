package tickets

import (
	. "tickets/pkg/web"
)

func main() {

	// Start a goroutine for an asynchronous listen to have the CLI
	go EnableHandlers()
}
