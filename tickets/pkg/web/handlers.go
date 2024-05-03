package web

import (
	"encoding/json"
	"fmt"
	"strconv"
	. "tickets/pkg/BDD"
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
	http.HandleFunc(RouteListCreerTickets, CreerTicketsHandler)

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

//
//---------------------------------------------------------------------------------
//

func IndexHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodGet {

		templates.ExecuteTemplate(w, "menu.html", nil)

	} else {
		http.Redirect(w, r, RouteIndex, http.StatusSeeOther)
	}

}

//
//---------------------------------------------------------------------------------
//

func RecupTicketsHandler(w http.ResponseWriter, r *http.Request) {

	// Récupérer les cookies de la requête
	var apikey = getApikeyFromCookie(w, r)

	if apikey == "" {
		var msg = "Votre apikey est null, veuiller vous connecter sur le site officiel avant"
		http.Redirect(w, r, RouteIndex+"?message="+msg, http.StatusSeeOther)
		Log.Error(msg)
		return
	}

	var role = getRoleFromApikey(apikey)
	if role > 3 {
		var msg = "Vous n'avez pas accès au listing des tickets"
		http.Redirect(w, r, RouteIndex+"?message="+msg, http.StatusSeeOther)
		Log.Error(msg)
		return
	}

	if r.Method == http.MethodGet {

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

//
//---------------------------------------------------------------------------------
//

func CreerTicketsHandler(w http.ResponseWriter, r *http.Request) {

	var bdd Db

	if r.Method == http.MethodGet {
		result, err := bdd.SelectDB(CATEGORIES, []string{"*"}, nil, nil)

		if err != nil {
			Log.Error("Impossible de sélectionner les catégories")
			http.Redirect(w, r, RouteIndex, http.StatusSeeOther)
			return
		}

		templates.ExecuteTemplate(w, "creerTicket.html", map[string]interface{}{
			"result":  result,
			"message": nil,
		})
	} else if r.Method == http.MethodPost {

		var params struct {
			Description string `json:"description"`
			Categorie   string `json:"categorie"`
		}

		err := json.NewDecoder(r.Body).Decode(&params)
		if err != nil {
			var msg = "Erreur lors de la lecture du corps de la requête 1"
			http.Error(w, msg, http.StatusBadRequest)
			Log.Error(msg)
			return
		}

		description := params.Description
		var cat = stringToInt(params.Categorie, w)
		if cat == -1 {
			return
		}

		id_categorie := cat

		apiKey := r.Header.Get("apikey")
		if apiKey == "" {
			var msg = "Missing API key"
			http.Error(w, msg, http.StatusUnauthorized)
			Log.Error(msg)
			return
		}

		var idUser = getIdUserFromApikey(apiKey)

		if idUser == -1 {
			return
		}

		var role = getRoleFromApikey(apiKey)
		if role == -1 {
			return
		}

		if role == 1 || role == 2 {
			var msg = "Vous ne pouvez pas créer un ticket en étant un admin"
			http.Error(w, msg, http.StatusBadRequest)
			Log.Error(msg)
			return
		}

		if !CreateTickets(idUser, description, id_categorie) {
			var msg = "Error when creating ticket"
			http.Error(w, msg, http.StatusBadRequest)
			Log.Error(msg)
			return
		}

		var msg = "Ticket créé avec succès"
		w.WriteHeader(http.StatusCreated)
		fmt.Fprintf(w, msg)
		return

	}

}

//
//----------------------------------------------------------------------------------------------------------------------
//

func stringToInt(leString string, w http.ResponseWriter) int {
	categorieInt, err := strconv.Atoi(leString)
	if err != nil {
		var msg = "Erreur lors de la conversion de string vers int"
		http.Error(w, msg, http.StatusBadRequest)
		Log.Error(msg)
		return -1
	}

	return categorieInt
}

func getIdUserFromApikey(apikey string) int64 {
	var bdd Db

	var condition = "apikey='" + apikey + "'"
	user, err := bdd.SelectDB(UTILISATEUR, []string{"id_user"}, nil, &condition)

	if err != nil {
		Log.Error("Error when trying to retrieve the id user from apikey")
		return -1
	}

	if len(user) > 0 {
		return user[0]["id_user"].(int64)
	} else {
		Log.Error("User doesn't return anything")
		return -1
	}
}

func getRoleFromApikey(apikey string) int64 {
	var bdd Db

	var condition = "apikey='" + apikey + "'"
	user, err := bdd.SelectDB(UTILISATEUR, []string{"id_role"}, nil, &condition)

	if err != nil {
		Log.Error("Error when trying to retrieve the id user from apikey")
		return -1
	}

	if len(user) > 0 {
		return user[0]["id_role"].(int64)
	} else {
		Log.Error("User doesn't return anything")
		return -1
	}
}

func getApikeyFromCookie(w http.ResponseWriter, r *http.Request) string {
	cookies := r.Cookies()

	var apikey string
	var leBool = false
	for _, cookie := range cookies {
		if cookie.Name == "apikey" {
			apikey = cookie.Value
			leBool = true
		}
	}

	if leBool == false {
		var msg = "Need to apikey"
		http.Redirect(w, r, RouteIndex+"?message="+msg, http.StatusSeeOther)
		Log.Error(msg)
		return ""
	}

	return apikey
}

func getApikeyFromHeader(w http.ResponseWriter, r *http.Request) string {

	apiKey := r.Header.Get("apikey")
	if apiKey == "" {
		var msg = "Missing API key"
		http.Error(w, msg, http.StatusUnauthorized)
		Log.Error(msg)
		return ""
	}

	return apiKey

}
