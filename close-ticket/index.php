<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include(ROOT . '/resources/tickets/get-ticket-information.php');

if (!isset($ticket))
{
    header('Location: /view-tickets/');
    exit;
}

include('close.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Resolve Ticket</title>
        
        <meta name="description" content="">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .description-input
            {
                width: 100%;
                min-height: 150px;
                resize: vertical;
            }
            
            .licensed
            {
                color: #4e4;
                background-color: #000;
                padding: 0 0.5em;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script></script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/ticket/?id=<?php echo htmlspecialchars($_GET['id']); ?>">&larr; Return to Ticket <?php echo htmlspecialchars($_GET['id']); ?></a>
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
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="column l8 m12 padding-small">
                <div class="bg-white shadow section">
                    <div class="content-section padding-small">
                        <h2>Resolution Description:</h2>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
                            <input name="ticket-id" value="<?php echo htmlspecialchars($_GET['id']); ?>" style="display:none">
                            <textarea class="description-input" placeholder="A description of how the ticket was resolved." name="description"></textarea>
                            <p><button type="submit" name="submitted" value="1">Close Ticket</button></p>
                            <?= $resolutionMessage ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>