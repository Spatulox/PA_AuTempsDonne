package manager

import (
	"fmt"
	"strconv"
	. "tickets/pkg/BDD"
	. "tickets/pkg/const"
	. "tickets/pkg/models"
	"time"
)

func CreateTickets(idOwner int64, description string, categorie int) bool {
	var etape = "1"

	var bdd Db

	if !CheckUserExist(int(idOwner)) {
		return false
	}

	if !checkCategorie(categorie) {
		return false
	}

	var idOwnerString = fmt.Sprintf("%d", idOwner)
	var categorieString = fmt.Sprintf("%d", categorie)

	var date = getCurrentDateTime()

	Log.Debug("ID Owner : " + idOwnerString)

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

	if len(result) == 0 {
		return nil
	}

	tickets := returnTickets(result)

	return tickets

}

func RecupMyTicketAdmin(idUser int64) []Tickets {

	var condition string

	var result []map[string]interface{}
	var err error
	var bdd Db

	condition = fmt.Sprintf("id_user_admin=%d AND id_etape !=3 AND id_etape !=4", idUser)
	result, err = bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	tickets := returnTickets(result)

	return tickets

}

func RecupClosedTickets() []Tickets {

	var condition string

	var result []map[string]interface{}
	var err error
	var bdd Db

	condition = fmt.Sprintf("id_etape =3 OR id_etape =4")
	result, err = bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	tickets := returnTickets(result)

	return tickets

}

func RecupMyTickets(idUser int64) []Tickets {

	var condition string

	var result []map[string]interface{}
	var err error
	var bdd Db

	condition = fmt.Sprintf("id_user_owner=%d", idUser)
	result, err = bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	tickets := returnTickets(result)
	return tickets

}

func returnTickets(result []map[string]interface{}) []Tickets {

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

		tiocket.OwnerStr = getEmailFromId(sResult["id_user_owner"].(int64))

		idUserAdmin, ok := sResult["id_user_admin"].(int64)
		if !ok {
			tiocket.IdAdmin = 0
			//Log.Debug("Impossible de caster id_user_admin en int64")
		} else {
			tiocket.IdAdmin = idUserAdmin
		}

		if tiocket.IdAdmin != 0 {
			tiocket.AdminStr = getEmailFromId(idUserAdmin)
		} else {
			tiocket.AdminStr = "N/A"
		}

		tiocket.IdEtape = sResult["id_etape"].(int64)
		tiocket.EtapeStr = SelectEtapeStr(sResult["id_etape"].(int64))

		tiocket.IdCategorie = sResult["id_categorie"].(int64)
		tiocket.CategorieStr = SelectCategorieStr(sResult["id_categorie"].(int64))

		tickets = append(tickets, tiocket)
	}
	return tickets
}

func RecupNotAssignTickets() []Tickets {

	result := RecupTickets(nil)

	theTickets := make([]Tickets, 0, len(result))

	for _, tickets := range result {

		if CheckTicketAssign(tickets.IdTicket) == 0 {
			theTickets = append(theTickets, tickets)
		}

	}

	return theTickets

}

//
//---------------------------------------------------------------------------------
//

func RecupState() []Etats {

	var result []map[string]interface{}
	var err error
	var bdd Db

	result, err = bdd.SelectDB(ETAPES, []string{"*"}, nil, nil)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	theState := make([]Etats, 0, len(result))

	for _, r := range result {

		etat := Etats{
			Id:  r["id_etape"].(int64),
			Nom: r["nom_etape"].(string),
		}

		theState = append(theState, etat)

	}

	return theState
}

func RecuptCategories() []Categories {

	var bdd Db

	result, err := bdd.SelectDB(CATEGORIES, []string{"*"}, nil, nil)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return nil
	}

	theCat := make([]Categories, 0, len(result))

	for _, r := range result {

		cat := Categories{
			Id:  r["id_categorie"].(int64),
			Nom: r["categorie"].(string),
		}

		theCat = append(theCat, cat)

	}

	return theCat
}

//
//---------------------------------------------------------------------------------
//

func RecupConversation(idTicket int) (Conversation, error) {
	var ticket1 = RecupTickets(&idTicket)

	if len(ticket1) == 0 {
		return Conversation{}, fmt.Errorf("Nothing")
	}

	ticket := ticket1[0]

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

	if !CheckTicketExist(idTicket) {
		return false
	}

	var date = getCurrentDateTime()
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	bdd.UpdateDB(TICKETS, []string{"date_cloture"}, []string{date}, &condition)
	Log.Infos("Ticket Closure updated")
	return true
}

func RemoveDateClosure(idTicket int) bool {
	var bdd Db

	if !CheckTicketExist(idTicket) {
		return false
	}
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	bdd.UpdateDB(TICKETS, []string{"date_cloture"}, []string{"NULL"}, &condition)
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

	leBool = CheckTicketExist(idTicket)
	if !leBool {
		return false
	}

	// Update it
	var etapeString = fmt.Sprintf("%d", newEtape)
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_etape"}, []string{etapeString}, &condition)
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

	if !CheckTicketExist(idTicket) {
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

	leBool := CheckTicketExist(idTicket)
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

	if CheckTicketState(idTicket) == -1 {
		return false
	}

	var bdd Db

	leBool := CheckTicketExist(idTicket)
	if !leBool {
		return false
	}

	leBool = CheckUserExist(idUser)
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

func AssignTicket(idTicket int, idUser int64) bool {

	if !CheckTicketExist(idTicket) {
		Log.Error("Id Ticket don't exist")
		return false
	}

	if !CheckUserExist(int(idUser)) {
		Log.Error("L'utilisateur n'existe pas")
		return false
	}

	var bdd Db

	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_user_admin"}, []string{strconv.Itoa(int(idUser))}, &condition)
	return true
}

//
//---------------------------------------------------------------------------------
//

func CheckTicketExist(idTicket int) bool {

	ticket := RecupTickets(&idTicket)

	if len(ticket) == 0 {
		var msg = fmt.Sprintf("The ticket '%d' don't exist", idTicket)
		Log.Error(msg)
		return false
	}

	return true
}

func CheckTicketAssign(idTicket int64) int {

	var bdd Db
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	user, err := bdd.SelectDB(TICKETS, []string{"id_user_admin"}, nil, &condition)

	if err != nil {
		Log.Error("Error when fetching data")
		return -1
	}

	if len(user) == 0 {
		Log.Error("Error, no tickets like that ??")
		return -1
	}

	idAdmin, ok := user[0]["id_user_admin"].(int64)
	if !ok {
		idAdmin = 0
		//Log.Debug("Impossible de caster id_user_admin en int64")
	}

	if err != nil {
		var smg = "Erreur lors de la converstion en int"
		Log.Error(smg)
		return -1
	}

	// Si y'a quelqu'un
	if idAdmin > 0 {
		return 1
	}
	return 0

}

func CheckTicketState(idTicket int) int {

	var bdd Db
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	ticket, err := bdd.SelectDB(TICKETS, []string{"id_etape"}, nil, &condition)

	if err != nil {
		Log.Error("Error when selecting Ticket to check the state")
		return -1
	}

	if len(ticket) == 0 {
		Log.Error("No ticket with this ID")
		return -1
	}

	idStateInt := ticket[0]["id_etape"].(int64)

	return int(idStateInt)

}

func CheckUserExist(idUser int) bool {

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

func getEmailFromId(idUser int64) string {
	var bdd Db

	condition := fmt.Sprintf("id_user=%d", idUser)
	result, err := bdd.SelectDB(UTILISATEUR, []string{"email"}, nil, &condition)

	if err != nil || result == nil {
		Log.Error("Erreur lors de la lecture de la Base de donnée", err)
		return ""
	}

	if len(result) == 0 {
		Log.Error("Pas d'utilisateur avec cet id")
		return ""
	}

	email := result[0]["email"].(string)

	return email

}
