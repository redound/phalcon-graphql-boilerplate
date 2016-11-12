<?php

namespace App\Constants;

class Types extends \Schema\Definition\Types
{
    const QUERY = "Query";
    const VIEWER = "Viewer";

    const PROJECT = "Project";
    const PROJECT_STATE_ENUM = "ProjectStateEnum";

    const TICKET = "Ticket";
    const TICKET_STATE_ENUM = "TicketStateEnum";
}
