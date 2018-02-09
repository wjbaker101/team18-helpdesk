<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include('get-ticket.php');

if (!isset($ticket))
{
    header('Location: /view-tickets/');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Ticket</title>
        
        <meta name="description" content="">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style></style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script>
            const onDeleteTicketButtonClick = () =>
            {
                const confirmation = confirm('Are you sure you would like to permanently delete this ticket?');
                
                if (confirmation)
                {
                    const http = new XMLHttpRequest() || new ActiveXObject('Microsoft.XMLHTTP');
                    
                    http.onreadystatechange = () =>
                    {
                        if (http.readyState === XMLHttpRequest.DONE)
                        {
                            if (http.status === 200)
                            {
                                const response = http.responseText;

                                document.querySelector('.delete-ticket-message').innerHTML = response;
                            }
                        }
                    };
                    
                    const ticketID = <?= $ticket['TicketID'] ?>;

                    http.open('GET', `delete-ticket.php?id=${ticketID}`, true);

                    http.send();
                }
            };
            
            window.addEventListener('load', () =>
            {
                const deleteTicketButton = document.querySelector('.delete-ticket-button');
                
                deleteTicketButton.addEventListener('click', onDeleteTicketButtonClick);
            });
        </script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/view-tickets/">&larr; Return to Tickets Overview</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width clearfix">
            <div class="sidebar float-left padding-small">
                <div class="bg-white shadow">
                    <div class="content-section padding-small">
                        <h1>Ticket <?= $ticket['TicketID'] ?></h1>
                        <p><strong><?= $ticket['Summary'] ?></strong></p>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <span class="cell-middle"><?= (new DateTime($ticket['EntryDate']))->format('d/m/Y H:i') ?></span>
                            <i class="status-<?= $ticketStatus ?>"></i>
                        </p>
                        <p>
                            <strong>Priority:</strong>
                            <i class="priority-<?= $ticketPriority ?>"></i>
                        </p>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Problem Type</strong><br>
                            <span><?= $ticket['ProblemType'] ?></span>
                        </p>
                        <p>
                            <strong>Hardware Serial ID</strong><br>
                            <span><?= $ticket['HardwareSerialID'] ?></span>
                        </p>
                        <p>
                            <strong>Operating System ID</strong><br>
                            <span><?= $ticket['OperatingSystemID'] ?></span>
                        </p>
                        <p>
                            <strong>Software ID</strong><br>
                            <span><?= $ticket['SoftwareID'] ?></span>
                        </p>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Created By</strong><br>
                            <span><?= $ticket['HelpdeskOperator_FirstName'] . ' ' . $ticket['HelpdeskOperator_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact Number</strong><br>
                            <span><?= $ticket['TelephoneNumber'] ?></span>
                        </p>
                        <?php if ($ticket['AssignedSpecialist'] !== null) { ?>
                        <p>
                            <strong>Assigned Specialist</strong><br>
                            <span><?= $ticket['AssignedSpecialist_FirstName'] . ' ' . $ticket['AssignedSpecialist_Surname'] ?></span>
                        </p>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="main-content padding-small">
                <div class="bg-white shadow">
                    <?php if ($ticketStatus === 'closed') { ?>
                    <div class="content-section padding-small">
                        <h2>Resolution</h2>
                        <h4>Description</h4>
                        <p><?= $ticket['Message'] ?></p>
                        <p>
                            <strong>Resolved By: </strong>
                            <span><?= $ticket['ResolutionEmployee_FirstName'] . ' ' . $ticket['ResolutionEmployee_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact: </strong>
                            <span><?= $ticket['ResolutionEmployee_TelephoneNumber'] ?></span>
                        </p>
                    </div>
                    <?php } ?>
                    <div class="content-section padding-small">
                        <h2>Call Logs</h2>
                    </div>
                    <div class="content-section padding-small">
                        <h2>Actions</h2>
                        <button class="delete-ticket-button">Delete Ticket</button>
                        <p class="delete-ticket-message text-error"></p>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>