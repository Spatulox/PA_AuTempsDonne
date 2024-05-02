package manager

import (
	"fmt"
	. "tickets/pkg/BDD"
	. "tickets/pkg/const"
	. "tickets/pkg/models"
)

func CreateTickets() {

}

func RecupTickets(idTicket *int) []Tickets {

	var condition string

	if idTicket != nil {
		condition = fmt.Sprintf("id_ticket=%d", *idTicket)
	}

	var bdd Db
	result, err := bdd.SelectDB(TICKETS, []string{"*"}, nil, &condition)

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
			DateCloture:  sResult["date_cloture"].(string),
			IdOwner:      sResult["id_user_owner"].(int64),
			IdAdmin:      sResult["id_user_admin"].(int64),
			IdEtape:      sResult["id_etape"].(int64),
			IdCategorie:  sResult["id_categorie"].(int64),
		}

		tickets = append(tickets, tiocket)
	}

	return tickets

}

//
//---------------------------------------------------------------------------------
//

func UpdateTicketsDateClosure(newDate string) {
	//var bdd Db
}

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
		return false
	}

	// Check if the ticket exist
	ticket := RecupTickets(&idTicket)

	if len(ticket) == 0 {
		return false
	}

	// Update it
	var etapeString = fmt.Sprintf("%d", newEtape)
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_etape"}, []string{etapeString}, &condition, true)

	return true
}

func UpdateTicketsCategorie(newCategorie int, idTicket int) bool {

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
		return false
	}

	// Check if the ticket exist
	ticket := RecupTickets(&idTicket)

	if len(ticket) == 0 {
		return false
	}

	// Update it
	var etapeString = fmt.Sprintf("%d", newCategorie)
	var condition = fmt.Sprintf("id_ticket=%d", idTicket)
	bdd.UpdateDB(TICKETS, []string{"id_categorie"}, []string{etapeString}, &condition, true)

	return true

}

func UpdateTicketsDescription(newDesc string, idTicket int) bool {

	var bdd Db

	ticket := RecupTickets(&idTicket)

	if len(ticket) == 0 {
		Log.Debug("La BITE")
		return false
	}

	var condition = fmt.Sprintf("id_ticket=%d", idTicket)

	bdd.UpdateDB(TICKETS, []string{"description"}, []string{newDesc}, &condition)

	return true
}

//
//---------------------------------------------------------------------------------
//

func AddMessageTickets() {

}
