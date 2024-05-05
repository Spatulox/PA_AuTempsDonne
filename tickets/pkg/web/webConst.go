package web

import (
	"html/template"
)

const (
	// Global things
	PORT       = "8085"
	RouteIndex = "/"

	RouteConnexion   = "http://localhost:8083/HTML/signup_login.php"
	RouteIndexTicket = "http://localhost:8085"

	RouteListTickets      = "/list"
	RouteListCreerTickets = "/create"
	RouteClaimTicket      = "/claim"

	RouteConversationTicket = "/conversation"

	RouteCloseTicket = "/close"

	RouteAddMessageTicket    = "/message"
	RouteRequestFetchMessage = "/fetch/message"

	RouteResquestUpdate = "/fetch/update"

	// Route for reservation

	// Route for Rooms

	// Route for JSON

)

// New templates
var templates = template.Must(template.ParseGlob("html/templates/*.html"))
