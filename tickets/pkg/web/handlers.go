package web

import (
	"strconv"
	. "tickets/pkg/const"
	. "tickets/pkg/manager"
	//"encoding/json"
	"net/http"
	//"strconv"
	//"strings"
	//"time"
)

func EnableHandlers() {

	// Create the directory with static file like CSS and JS
	staticDir := http.Dir("html/src")
	staticHandler := http.FileServer(staticDir)
	http.Handle("/static/", http.StripPrefix("/static/", staticHandler))

	// Create all the handle to "listen" the right path using const in webConst.go
	http.HandleFunc(RouteIndex, IndexHandler)
	http.HandleFunc(RouteListTickets, RecupTicketsHandler)

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

func IndexHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodGet {

		templates.ExecuteTemplate(w, "menu.html", nil)

	} else {
		http.Redirect(w, r, RouteIndex, http.StatusSeeOther)
	}

}

func RecupTicketsHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodGet {

		//idTicket

		idTicketStr := r.URL.Query().Get("idTicket")

		if idTicketStr == "" {
			resultT := RecupTickets(nil)

			templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
			})
		} else {

			idTicketInt, err := strconv.Atoi(idTicketStr)
			if err != nil {
				http.Error(w, "Le paramètre 'idTicket' doit être un entier", http.StatusBadRequest)
				return
			}

			resultT := RecupTickets(&idTicketInt)

			templates.ExecuteTemplate(w, "oneTicket.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
			})
		}

	} else {
		http.Redirect(w, r, RouteIndex, http.StatusSeeOther)
	}

}
