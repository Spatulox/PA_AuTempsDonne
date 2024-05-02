package main

import (
	. "tickets/pkg/const"
	. "tickets/pkg/manager"
	. "tickets/pkg/web"
)

func main() {

	// Start a goroutine for an asynchronous listen to have the CLI
	//go EnableHandlers()
	go EnableHandlers()

	RecupTickets(nil)

	err := UpdateTicketsEtape(4, 2)

	if !err {
		Log.Error("C'est false 1")
	}

	err = UpdateTicketsCategorie(5, 5)

	if !err {
		Log.Error("C'est false 2")
	}

	var strong = "coucou"
	CreateTickets(1, strong, 1)

	AddMessageTickets("coucou", 5, 2)

	result, err2 := RecupConversation(5)

	if err2 != nil {
		Log.Error("Error")
	}
	println(result)
}
