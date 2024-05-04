package web

import (
	"encoding/json"
	"fmt"
	"strconv"
	"strings"
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
	http.HandleFunc(RouteClaimTicket, ClaimTicketHandler)
	http.HandleFunc(RouteConversationTicket, ConversationHandler)

	http.HandleFunc(RouteCloseTicket, CloseTicketHandler)

	http.HandleFunc(RouteAddMessageTicket, AjouterMessageHandler)

	http.HandleFunc(RouteRequestFetchMessage, SendMessageToJsonHandler)

	http.HandleFunc(RouteResquestUpdate, UpdateTicketHandler)

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

		var apikey = getApikeyFromCookie(w, r)

		var role = getRoleFromApikey(apikey)

		templates.ExecuteTemplate(w, "menu.html", map[string]interface{}{
			"role":    role,
			"message": nil,
		})

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

	ticketMe := r.URL.Query().Get("me")

	var role = getRoleFromApikey(apikey)
	if role > 3 {

		var idUser = getIdUserFromApikey(apikey)

		resultT := RecupMyTickets(idUser)

		templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
			"result":  resultT,
			"message": nil,
			"role":    role,
		})
		return
	}

	if r.Method == http.MethodGet {

		idTicketStr := r.URL.Query().Get("idTicket")
		ticketAssign := r.URL.Query().Get("assign")
		ticketClosed := r.URL.Query().Get("closed")

		if idTicketStr == "" && ticketAssign == "" && ticketMe == "" && ticketClosed == "" {
			resultT := RecupTickets(nil)

			templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
				"role":    role,
			})

		} else if ticketAssign == "false" {

			resultT := RecupNotAssignTickets()

			templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
				"role":    role,
			})

		} else if ticketMe == "true" {

			var idUser = getIdUserFromApikey(apikey)

			resultT := RecupMyTicketAdmin(idUser)

			templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
				"role":    role,
			})
		} else if ticketClosed == "true" {

			if role > 2 {
				var msg = "Vous ne pouvez pas faire ça"
				sendError(w, msg, http.StatusBadRequest)
				return
			}

			resultT := RecupClosedTickets()

			templates.ExecuteTemplate(w, "listTickets.html", map[string]interface{}{
				"result":  resultT,
				"message": nil,
				"role":    role,
			})

		} else {

			idTicketInt, err := strconv.Atoi(idTicketStr)
			if err != nil {
				http.Error(w, "Le paramètre 'idTicket' doit être un entier", http.StatusBadRequest)
				return
			}

			resultT := RecupTickets(&idTicketInt)

			states := RecupState()
			categories := RecuptCategories()

			templates.ExecuteTemplate(w, "oneTicket.html", map[string]interface{}{
				"result":    resultT,
				"message":   nil,
				"role":      role,
				"state":     states,
				"categorie": categories,
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
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		description := params.Description
		var cat = stringToInt(params.Categorie, w)
		if cat == -1 {
			return
		}

		id_categorie := cat

		apiKey := getApikeyFromHeader(w, r)

		var idUser = getIdUserFromApikey(apiKey)

		// Handle error
		if idUser == -1 {
			return
		}

		var role = getRoleFromApikey(apiKey)
		if role == -1 {
			return
		}

		// SECURITY
		if role == 1 || role == 2 {
			var msg = "Vous ne pouvez pas créer un ticket en étant un admin"
			http.Error(w, msg, http.StatusBadRequest)
			Log.Error(msg)
			return
		}

		if !CreateTickets(idUser, description, id_categorie) {
			var msg = "Error when creating ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var msg = "Ticket créé avec succès"

		sendMessage(w, msg)
		return

	}

}

//
// ----------------------------------------------------------------------------------------------------------------------
//

// Fetch
func ClaimTicketHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodGet {

		apiKey := getApikeyFromCookie(w, r)
		if apiKey == "" {
			return
		}

		if getRoleFromApikey(apiKey) > 2 {
			sendError(w, "Vous n'avez pas les droits pour claim un ticket", http.StatusBadRequest)
			return
		}
		idTicketStr := r.URL.Query().Get("idTicket")

		var idTicketInt = stringToInt(idTicketStr, w)

		if idTicketInt == -1 {
			var msg = "L'ID ticket est nul"
			http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
			return
		}

		if !CheckTicketExist(idTicketInt) {
			var msg = "L'ID ticket n'existe pas"
			http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
			return
		}

		check := CheckTicketAssign(int64(idTicketInt))

		if check == -1 {
			sendError(w, "Error", http.StatusBadRequest)
			return
		}

		if check == 1 {
			var msg = "Le ticket est déjà assigné à un admin"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		idUser := getIdUserFromApikey(apiKey)

		//Y4a pas assigné
		if !AssignTicket(idTicketInt, idUser) {
			sendError(w, "Error when assigning ticket", http.StatusConflict)
			return
		}

		if !UpdateTicketsEtape(2, idTicketInt) {
			var msg = "Ticket assigned, but the state can't be updated :/"
			sendMessage(w, msg)
			return
		}

		sendMessage(w, "Ticket assigned")

	} else {
		var msg = "Only GET request for ClaimTicket"
		sendError(w, msg, http.StatusBadRequest)
		return
	}
}

//
//----------------------------------------------------------------------------------------------------------------------
//

// Fetch
func CloseTicketHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodGet {

		apiKey := getApikeyFromCookie(w, r)
		if apiKey == "" {
			return
		}

		if getRoleFromApikey(apiKey) > 2 {
			sendError(w, "Vous n'avez pas les droits pour fermer un ticket", http.StatusBadRequest)
			return
		}

		idTicketStr := r.URL.Query().Get("idTicket")

		if idTicketStr == "" {
			var msg = "Vous devez spécifier l'id du ticket à fermer"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var idTicketInt = stringToInt(idTicketStr, w)

		if idTicketInt == -1 {

			var msg = "L'ID ticket est nul"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		if !CheckTicketExist(idTicketInt) {
			var msg = "L'ID ticket n'existe pas"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var state = CheckTicketState(idTicketInt)
		if state == 3 || state == 4 {
			var msg = "Ce ticket est déjà fermé"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		if !UpdateTicketsEtape(3, idTicketInt) {
			var msg = "Impossible de fermer le ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		if !UpdateTicketsDateClosure(idTicketInt) {
			var msg = "Impossible d'ajouter une date de cloture au ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var msg = "Ticket Closed"
		sendMessage(w, msg)
		return

	} else {
		var msg = "Only GET request to close a ticket"
		sendError(w, msg, http.StatusBadRequest)
		return
	}

}

//
//----------------------------------------------------------------------------------------------------------------------
//

func ConversationHandler(w http.ResponseWriter, r *http.Request) {

	apikey := getApikeyFromCookie(w, r)

	role := getRoleFromApikey(apikey)

	idUser := getIdUserFromApikey(apikey)

	idTicketStr := r.URL.Query().Get("idTicket")

	if idTicketStr == "" {
		var msg = "Vous devez spécifier l'id du ticket à voir"
		http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
		return
	}

	var idTicket int
	if role > 2 {
		// Pas admin (get le ticket depuis l'apikey => id_user)

		idTicket1, err := strconv.Atoi(idTicketStr)

		if err != nil {
			var msg = "Impossible to cast the idTicket"
			http.Redirect(w, r, RouteIndex+"?message="+msg, http.StatusSeeOther)
			Log.Error(msg)
			return
		}

		idTicket = int(getIdTiketFromIdUser(idUser, int64(idTicket1)))

		if idTicket == -1 {
			var msg = "Ce ticket n'est pas à vous"
			http.Redirect(w, r, RouteIndex+"?message="+msg, http.StatusSeeOther)
			Log.Error(msg)
			return
		}

	} else {
		// Admin (get le ticket depuis l'addresse url)

		if idTicketStr == "" {
			var msg = "Vous devez spécifier l'id du ticket à voir"
			http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
			return
		}
		var err error
		idTicket, err = strconv.Atoi(idTicketStr)

		if err != nil {
			var msg = "Impossible to cast the idTicket from string to int64"
			http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
			Log.Error(msg)
			return
		}

		if checkIdTiketLinkToAdmin(idUser, int64(idTicket)) == -1 {
			var msg = "Ce ticket n'est pas à vous"
			http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
			Log.Error(msg)
			return
		}

	}

	conv, err := RecupConversation(idTicket)

	if err != nil {
		var msg = "Impossible to retrieve conversation"
		http.Redirect(w, r, RouteListTickets+"?message="+msg, http.StatusSeeOther)
		Log.Error(msg)
		return
	}

	templates.ExecuteTemplate(w, "conversation.html", map[string]interface{}{
		"result":  conv,
		"message": nil,
		"vous":    idUser,
		"role":    role,
	})

}

//
//---------------------------------------------------------------------------------
//

func SendMessageToJsonHandler(w http.ResponseWriter, r *http.Request) {

	apikey := getApikeyFromHeader(w, r)

	idUser := getIdUserFromApikey(apikey)

	if r.Method == http.MethodGet {

		idTicketStr := r.URL.Query().Get("idTicket")

		if idTicketStr == "" {
			var msg = "Spécifier l'id du ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		// Check if the ticket exist and is link to an admin or the right person
		var bdd Db
		//ORDER BY date_message
		var condition = fmt.Sprintf("id_ticket=%s AND id_user_owner=%d OR id_user_admin=%d", idTicketStr, idUser, idUser)
		result, err := bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)

		if err != nil {
			return
		}

		if len(result) == 0 {
			return
		}

		idTicket := stringToInt(idTicketStr, w)
		if idTicket == -1 {
			return
		}

		resultT, err := RecupConversation(idTicket)

		if err != nil {
			return
		}

		dataToSend, err := json.Marshal(resultT)

		if err != nil {
			return
		}

		w.Header().Set("Content-Type", "application/json")
		_, err = w.Write(dataToSend)

		if err != nil {
			http.Error(w, err.Error(), http.StatusInternalServerError)
			return
		}
		return

	} else {
		var msg = "Only GET request to request a message"
		sendError(w, msg, http.StatusBadRequest)
		return
	}

}

//
//----------------------------------------------------------------------------------------------------------------------
//

func AjouterMessageHandler(w http.ResponseWriter, r *http.Request) {

	// idTicket int, idUser int, message string

	if r.Method == http.MethodPost {

		var params struct {
			IdTicket string `json:"id_ticket"`
			Message  string `json:"message"`
		}

		err := json.NewDecoder(r.Body).Decode(&params)
		if err != nil {
			var msg = "Erreur lors de la lecture du corps de la requête"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var idTicket = stringToInt(params.IdTicket, w)
		if idTicket == -1 {
			return
		}

		var apikey = getApikeyFromHeader(w, r)
		if apikey == "" {
			return
		}

		var idUser = getIdUserFromApikey(apikey)

		if CheckTicketState(idTicket) == -1 {
			var msg = "Impossible de récupérer l'étape du ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var state = CheckTicketState(idTicket)
		if state == 3 || state == 4 {
			var msg = "Vous ne pouvez pas ajouter de message à un ticket fermé"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		if !CheckUserExist(int(idUser)) {
			var msg = "Cet utilisateur n'existe pas"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		if !AddMessageTickets(params.Message, idTicket, int(idUser)) {
			var msg = "Impossible d'ajouter le message au ticket"
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		var msg = "Message ajouté avec succès"
		sendMessage(w, msg)
		Log.Infos(msg)
		return

	} else {
		var msg = "Only POST request to ass a mesage"
		sendError(w, msg, http.StatusBadRequest)
		return
	}

}

//
//----------------------------------------------------------------------------------------------------------------------
//

func UpdateTicketHandler(w http.ResponseWriter, r *http.Request) {

	if r.Method == http.MethodPost {

		var params struct {
			Desc     string `json:"desc"`
			Cat      string `json:"cat"`
			State    string `json:"state"`
			IdTicket string `json:"id_ticket"`
		}

		err := json.NewDecoder(r.Body).Decode(&params)
		if err != nil {
			var msg = "Erreur lors de la lecture du corps de la requête "
			sendError(w, msg, http.StatusBadRequest)
			return
		}

		idTicketInt := stringToInt(params.IdTicket, w)
		if idTicketInt == -1 {
			return
		}

		if params.State != "" {
			stateInt := stringToInt(params.State, w)
			if stateInt == -1 {
				return
			}

			if !UpdateTicketsEtape(stateInt, idTicketInt) {
				var msg = "Cannot update the state of the ticket"
				sendError(w, msg, http.StatusInternalServerError)
				return
			}

			if stateInt == 3 || stateInt == 4 {

				if !UpdateTicketsDateClosure(idTicketInt) {
					var msg = "Impossible d'ajouter une date de cloture au ticket"
					sendError(w, msg, http.StatusBadRequest)
					return
				}

			} else {
				if !RemoveDateClosure(idTicketInt) {
					var msg = "Une erreur est arrivé lors du check de la date de cloture"
					sendError(w, msg, http.StatusBadRequest)
					return
				}
			}
		}

		if params.Cat != "" {
			catInt := stringToInt(params.Cat, w)
			if catInt == -1 {
				return
			}

			if !UpdateTicketsCategorie(catInt, idTicketInt) {
				var msg = "Cannot update the categorie of the ticket"
				sendError(w, msg, http.StatusInternalServerError)
				return
			}
		}

		if params.Desc != "" {

			newStr := strings.ReplaceAll(params.Desc, "'", " ")

			if !UpdateTicketsDescription(newStr, idTicketInt) {
				var msg = "Cannot update the description of the ticket"
				sendError(w, msg, http.StatusInternalServerError)
				return
			}
		}

		var msg = "Updated"
		sendMessage(w, msg)
		Log.Infos(msg)
		return

	} else {
		var msg = "Only POST request to update ticket"
		sendError(w, msg, http.StatusBadRequest)
		return
	}
}

//
//----------------------------------------------------------------------------------------------------------------------
//

// ----------------- UTILS -----------------

func stringToInt(leString string, w http.ResponseWriter) int {
	categorieInt, err := strconv.Atoi(leString)
	if err != nil {
		var msg = "Erreur lors de la conversion de string vers int"
		sendError(w, msg, http.StatusConflict)
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
		sendError(w, msg, http.StatusUnauthorized)
		return ""
	}

	var bdd Db
	var condition = "apikey='" + apiKey + "'"
	result, err := bdd.SelectDB(UTILISATEUR, []string{"id_user"}, nil, &condition)

	if err != nil {
		Log.Error("Erreur lors de la sélection de l'id user via apikey")
		return ""
	}

	if len(result) == 0 {
		Log.Error("La requête n'a rien renvoyé")
		return ""
	}

	return apiKey

}

func getIdTiketFromIdUser(idUser int64, idTicket int64) int64 {
	var bdd Db
	// AND id_etape!=3 AND id_etape!=4
	var condition = fmt.Sprintf("id_user_owner=%d AND id_ticket=%d", idUser, idTicket)
	result, err := bdd.SelectDB(TICKETS, []string{"id_ticket"}, nil, &condition)

	if err != nil {
		return -1
	}

	if len(result) == 0 {
		return -1
	}

	return result[0]["id_ticket"].(int64)
}

func checkIdTiketLinkToAdmin(idUser int64, idTicket int64) int64 {
	var bdd Db
	// AND id_etape!=3 AND id_etape!=4
	var condition = fmt.Sprintf("id_user_admin=%d AND id_ticket=%d", idUser, idTicket)
	result, err := bdd.SelectDB(TICKETS, []string{"id_ticket"}, nil, &condition)

	if err != nil {
		return -1
	}

	if len(result) == 0 {
		return -1
	}

	return result[0]["id_ticket"].(int64)
}

func sendError(w http.ResponseWriter, msg string, status int) {
	http.Error(w, msg, status)
	Log.Error(msg)
	return
}

func sendMessage(w http.ResponseWriter, msg string) {
	w.Header().Set("Content-Type", "text/plain; charset=utf-8")
	w.WriteHeader(http.StatusOK)
	fmt.Fprint(w, msg)
}
