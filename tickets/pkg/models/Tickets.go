package models

import (
	"fmt"
	. "tickets/pkg/BDD"
	. "tickets/pkg/const"
)

type Tickets struct {
	IdTicket     int64
	Description  string
	DateCreation string
	DateCloture  string
	IdOwner      int64
	IdAdmin      int64
	IdEtape      int64 // En cours, fin, etc...
	EtapeStr     string
	IdCategorie  int64 // Urgent
	CategorieStr string
}

func SelectEtapeStr(etapeId int64) string {
	var bdd Db
	var conditin = fmt.Sprintf("id_etape=%d", etapeId)
	result, err := bdd.SelectDB(ETAPES, []string{"nom_etape"}, nil, &conditin)

	if err != nil {
		return ""
	}

	return result[0]["nom_etape"].(string)

}

func SelectCategorieStr(categorieId int64) string {
	var bdd Db
	var condition = fmt.Sprintf("id_categorie=%d", categorieId)
	result, err := bdd.SelectDB(CATEGORIES, []string{"categorie"}, nil, &condition)

	if err != nil {
		return ""
	}

	return result[0]["categorie"].(string)

}
