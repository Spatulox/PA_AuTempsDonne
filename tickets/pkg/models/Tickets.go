package models

type Tickets struct {
	IdTicket     int64
	Description  string
	DateCreation string
	DateCloture  string
	IdOwner      int64
	IdAdmin      int64
	IdEtape      int64 // En cours, fin, etc...
	IdCategorie  int64 // Urgent
}
