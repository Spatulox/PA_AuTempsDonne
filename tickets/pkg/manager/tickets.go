package manager

import (
	"fmt"
	"strconv"
	. "tickets/pkg/BDD"
	. "tickets/pkg/const"
	. "tickets/pkg/models"
	"time"
)

func CreateTickets(idOwner int, description string, categorie int) bool {
	var etape = "1"

	var bdd Db

	if !checkUserExist(idOwner) {
		return false
	}

	if !checkCategorie(categorie) {
		return false
	}

	var idOwnerString = fmt.Sprintf("%d", idOwner)
	var categorieString = fmt.Sprintf("%d", categorie)

	var date = getCurrentDateTime()

	bdd.InsertDB(TICKETS, []string{"id_user_owner", "description", "id_categorie", "id_etape", "date_creation"}, []string{idOwnerString, description, categorieString, etape, date})
	Log.Infos("Ticket created")
	return true
}

//
//---------------------------------------------------------------------------------
//

func RecupTickets(idTicket *int) []Tickets {

	var condition string

	var result []map[string]interface{}
	var err error
	var bdd Db

	if idTicket != nil {
		condition = fmt.Sprintf("id_ticket=%d", *idTicket)
		result, err = bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)
	} else {
		result, err = bdd.SelectDB(TICKETS, []string{"*"}, nil, nil)
	}

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	tickets := make([]Tickets, 0, len(result))

	for _, sResult := range result {
		tiocket := Tickets{
			IdTicket:     sResult["id_ticket"].(int64),
			Description:  sResult["description"].(string),
			DateCreation: sResult["date_creation"].(string),
		}

		dateCloture, ok := sResult["date_cloture"].(string)
		if ok && dateCloture != "" {
			tiocket.DateCloture = dateCloture
		} else {
			tiocket.DateCloture = ""
		}

		tiocket.IdOwner = sResult["id_user_owner"].(int64)

		idUserAdmin, ok := sResult["id_user_admin"].(string)
		if ok && idUserAdmin != "" {
			// Convertir la chaîne de caractères en int64
			tiocket.IdAdmin, _ = strconv.ParseInt(idUserAdmin, 10, 64)
		} else {
			tiocket.IdAdmin = 0
		}

		tiocket.IdEtape = sResult["id_etape"].(int64)
		tiocket.IdCategorie = sResult["id_categorie"].(int64)

		tickets = append(tickets, tiocket)
	}

	return tickets

}

func RecupConversation(idTicket int) (Conversation, error) {
	var ticket = RecupTickets(&idTicket)[0]

	var bdd Db
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	result, err := bdd.SelectDB(DISCUSSION, []string{"*"}, nil, &condition)

	if err != nil {
		Log.Error("Impossible de sélectionne les données corespondantes au ticket")
		return Conversation{}, err
	}
	var message = make([]Message, 0, len(result))

	for _, r := range result {

		msg := Message{
			IdMessage:   r["id_message"].(int64),
			DateMessage: r["date_message"].(string),
			Text:        r["text"].(string),
			IdUser:      r["id_user"].(int64),
		}

		message = append(message, msg)
	}

	var conversation = Conversation{
		Ticket:   ticket,
		Messages: message,
	}

	return conversation, nil
}

//
//---------------------------------------------------------------------------------
//

func UpdateTicketsDateClosure(idTicket int) bool {
	var bdd Db

	if !checkTicketExist(idTicket) {
		return false
	}

	var date = getCurrentDateTime()
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	bdd.UpdateDB(TICKETS, []string{"date_closure"}, []string{date}, &condition)
	Log.Infos("Ticket Closure updated")
	return true
}

//
//---------------------------------------------------------------------------------
//

func UpdateTicketsEtape(newEtape int, idTicket int) bool {
	var bdd Db

	etapes, err := bdd.SelectDB(ETAPES, []string{"id_etape"}, nil, nil)

	if err != nil {
		return false
	}

	// Check if the etape exist in the DB
	leBool := false
	for _, etape := range etapes {
		if etape["id_etape"].(int64) == int64(newEtape) {
			leBool = true
			break
		}
	}

	if leBool == false {
		var msg = fmt.Sprintf("This idEtape '%d' don't exist", newEtape)
		Log.Error(msg)
		return false
	}

	leBool = checkTicketExist(idTicket)
	if !leBool {
		return false
	}

	// Update it
	var etapeString = fmt.Sprintf("%d", newEtape)
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_etape"}, []string{etapeString}, &condition, true)
	Log.Infos("Ticket Etape updated")
	return true
}

//
//---------------------------------------------------------------------------------
//

func UpdateTicketsCategorie(newCategorie int, idTicket int) bool {

	var bdd Db

	if !checkCategorie(newCategorie) {
		return false
	}

	if !checkTicketExist(idTicket) {
		return false
	}

	// Update it
	var etapeString = fmt.Sprintf("%d", newCategorie)
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_categorie"}, []string{etapeString}, &condition)
	Log.Infos("Ticket Categorie updated")
	return true

}

//
//---------------------------------------------------------------------------------
//

func UpdateTicketsDescription(newDesc string, idTicket int) bool {

	var bdd Db

	leBool := checkTicketExist(idTicket)
	if !leBool {
		return false
	}

	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	bdd.UpdateDB(TICKETS, []string{"description"}, []string{newDesc}, &condition)
	Log.Infos("Ticket Description updated")
	return true
}

//
//---------------------------------------------------------------------------------
//

func AddMessageTickets(message string, idTicket int, idUser int) bool {

	var bdd Db

	leBool := checkTicketExist(idTicket)
	if !leBool {
		return false
	}

	leBool = checkUserExist(idUser)
	if !leBool {
		return false
	}

	date := getCurrentDateTime()

	ticketString := fmt.Sprintf("%d", idTicket)
	userString := fmt.Sprintf("%d", idUser)

	bdd.InsertDB(DISCUSSION, []string{"text", "date_message", "id_user", "id_ticket"}, []string{message, date, userString, ticketString})
	Log.Infos("Added message to ticket")
	return true
}

//
//---------------------------------------------------------------------------------
//

func checkTicketExist(idTicket int) bool {

	ticket := RecupTickets(&idTicket)

	if len(ticket) == 0 {
		var msg = fmt.Sprintf("The ticket '%d' don't exist", idTicket)
		Log.Error(msg)
		return false
	}

	return true
}

func checkUserExist(idUser int) bool {

	var bdd Db
	var condition = fmt.Sprintf("id_user=%d", idUser)

	user, err := bdd.SelectDB(UTILISATEUR, []string{"id_user"}, nil, &condition)

	if err != nil || user == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return false
	}

	if len(user) == 0 {
		var msg = fmt.Sprintf("The user '%d' don't exist", idUser)
		Log.Error(msg)
		return false
	}

	return true
}

func checkCategorie(newCategorie int) bool {
	var bdd Db

	categories, err := bdd.SelectDB(CATEGORIES, []string{"id_categorie"}, nil, nil)

	if err != nil {
		return false
	}

	// Check if the categorie exist in the DB
	leBool := false
	for _, cat := range categories {
		if cat["id_categorie"].(int64) == int64(newCategorie) {
			leBool = true
			break
		}
	}

	if leBool == false {
		var msg = fmt.Sprintf("This idCategorie '%d' don't exist", newCategorie)
		Log.Error(msg)
		return false
	}

	return true
}

func getCurrentDateTime() string {
	currentTime := time.Now()
	return currentTime.Format("2006-01-02 15:04:05")
}
