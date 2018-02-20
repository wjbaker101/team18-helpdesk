<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include(ROOT . '/resources/tickets/get-ticket-information.php');

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
        
        <style>
            .licensed
            {
                color: #4e4;
                background-color: #000;
                padding: 0 0.5em;
            }
        </style>
        
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

                                $('.delete-ticket-message').html(response);
                                
                                window.location.href = '/view-tickets/';
                            }
                        }
                    };
                    
                    const ticketID = <?= $ticket['TicketID'] ?>;

                    http.open('GET', `/resources/tickets/delete-ticket.php?id=${ticketID}`, true);

                    http.send();
                }
            };
            
            window.addEventListener('load', () =>
            {
                $('.delete-ticket-button').click(onDeleteTicketButtonClick);
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
        <div class="content-width column-container">
            <div class="column l4 m12 padding-small">
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
                            <strong>Problem Type:</strong>
                            <span><?= $ticket['ProblemType'] ?></span>
                        </p>
                        <?php if ($ticket['HardwareSerialID'] !== null) { ?>
                        <p>
                            <strong>Hardware Serial ID:</strong>
                            <span><?= $ticket['HardwareSerialID'] ?></span>
                            <?= $hardwareLicense ?>
                        </p>
                        <?php } ?>
                        <?php if ($ticket['OperatingSystemID'] !== null) { ?>
                        <p>
                            <strong>Operating System:</strong>
                            <span><?= $ticket['OperatingSystemID'] ?> v<?= $ticket['OperatingSystemVersion'] ?></span>
                            <?= $osLicense ?>
                        </p>
                        <?php } ?>
                        <?php if ($ticket['SoftwareID'] !== null) { ?>
                        <p>
                            <strong>Software:</strong>
                            <span><?= $ticket['SoftwareID'] ?> v<?= $ticket['SoftwareVersion'] ?></span>
                            <?= $softwareLicense ?>
                        </p>
                        <?php } ?>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Opened By:</strong>
                            <span><?= $ticket['HelpdeskOperator_FirstName'] . ' ' . $ticket['HelpdeskOperator_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact:</strong>
                            <span><?= $ticket['HelpdeskOperator_TelephoneNumber'] ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="column l8 m12 padding-small">
                <?php if ($ticketStatus === 'closed') { ?>
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Resolution</h2>
                        <p><?= $ticket['Message'] ?></p>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Resolved By: </strong>
                            <span><?= $ticket['ResolutionEmployee_FirstName'] . ' ' . $ticket['ResolutionEmployee_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact: </strong>
                            <span><?= $ticket['ResolutionEmployee_TelephoneNumber'] ?></span>
                        </p>
                    </div>
                </div>
                <?php } ?>
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Full Ticket Details</h2>
                        <p><?= $ticket['Description'] ?></p>
                    </div>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Caller's Name:</strong>
                            <span><?= $ticket['Caller_FirstName'] . ' ' . $ticket['Caller_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact:</strong>
                            <span><?= $ticket['Caller_TelephoneNumber'] ?></span>
                        </p>
                    </div>
                    <?php if ($ticket['AssignedSpecialist'] !== null) { ?>
                    <div class="content-section padding-small">
                        <p>
                            <strong>Assigned Specialist:</strong>
                            <span><?= $ticket['AssignedSpecialist_FirstName'] . ' ' . $ticket['AssignedSpecialist_Surname'] ?></span>
                        </p>
                        <p>
                            <strong>Contact:</strong>
                            <span><?= $ticket['AssignedSpecialist_TelephoneNumber'] ?></span>
                        </p>
                        <?php $buttonText = ($ticket['AssignedSpecialist'] === null) ? 'Assign Specialist' : 'Reassign Specialist' ?>
                        <p><a href="/assign-specialist/?id=<?= $ticket['TicketID'] ?>"><button><?= $buttonText ?></button></a></p>
                    </div>
                    <?php } ?>
                </div>
                <?php if ($ticket['ResolutionID'] === null) { ?>
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Close Ticket</h2>
                        <p><a href="/close-ticket/?id=<?= $ticket['TicketID'] ?>"><button>Close Ticket</button></a></p>
                    </div>
                </div>
                <?php } ?>
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Call Logs
                            <a href="/create-call-log/?id=<?php echo htmlspecialchars($_GET['id']); ?>" title="Create a new call log for this ticket.">
                                <svg width="17" class="cell-middle" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                    <path fill="#000" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z"></path>
                                </svg>
                            </a>
                        </h2>
                    </div>
                    <?php include('get-call-logs.php'); ?>
                </div>
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Actions</h2>
                        <button class="delete-ticket-button">Delete Ticket</button>
                        <div class="delete-ticket-message"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>