package web

import (
	"html/template"
)

const (
	// Global things
	PORT       = "8085"
	RouteIndex = "/"

	RouteListTickets      = "/list"
	RouteListCreerTickets = "/create"
	RouteClaimTicket      = "/claim"

	RouteConversationTicket = "/conversation"

	RouteCloseTicket = "/close"

	RouteAddMessageTicket    = "/message"
	RouteRequestFetchMessage = "/fetch/message"

	// Route for reservation

	// Route for Rooms

	// Route for JSON

)

// New templates
var templates = template.Must(template.ParseGlob("html/templates/*.html"))
