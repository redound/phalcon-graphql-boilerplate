<?php

namespace App\Constants;

class Types extends \Schema\Definition\Types
{
    const QUERY = "Query";
    const VIEWER = "Viewer";

    const PROJECT = "Project";
    const PROJECT_CONNECTION = "ProjectConnection";
    const PROJECT_EDGE = "ProjectEdge";
    const PROJECT_STATE_ENUM = "ProjectStateEnum";

    const TICKET = "Ticket";
    const TICKET_CONNECTION = "TicketConnection";
    const TICKET_EDGE = "TicketEdge";
    const TICKET_STATE_ENUM = "TicketStateEnum";
}
