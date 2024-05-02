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

	Log.Debug("Coucou")

	RecupTickets(nil)

	err := UpdateTicketsEtape(4, 2)

	if !err {
		Log.Error("C'est false")
	}

	err = UpdateTicketsCategorie(5, 6)

	if !err {
		Log.Error("C'est false")
	}
}
