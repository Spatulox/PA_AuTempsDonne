package web

import (
	. "tickets/pkg/const"
	//"encoding/json"
	"net/http"
	//"strconv"
	//"strings"
	//"time"
)

func EnableHandlers() {

	// Create the directory with static file like CSS and JS
	staticDir := http.Dir("templates/src")
	staticHandler := http.FileServer(staticDir)
	http.Handle("/static/", http.StripPrefix("/static/", staticHandler))

	// Create all the handle to "listen" the right path using const in webConst.go
	//http.HandleFunc(RouteIndex, IndexHandler)

	// Reservation Handlers

	// Rooms Handlers

	// Json Handlers

	Log.Infos("Handlers Enabled")

	Log.Infos("Starting server on port " + PORT)
	err := http.ListenAndServe(":"+PORT, nil)
	if err != nil {
		Log.Error("Failed to start server: ", err)
		return
	}
	Log.Infos("Server stopped on port " + PORT)

}
