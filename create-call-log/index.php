<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include(ROOT . '/resources/tickets/get-ticket-information.php');

if ($employee === null)
{
    header ('Location: /users/login.php');
    exit;
}

if (!isset($ticket))
{
    header('Location: /view-tickets/');
    exit;
}

include('create-log.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - New Call Log</title>
        
        <meta name="description" content="Create a new call log for a ticket.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .ticket-panel
            {
                width: calc(100% - 300px);
            }
            
            .summary-input
            {
                width: 100%;
            }
            
            .description-input
            {
                width: 100%;
                min-height: 150px;
                resize: vertical;
            }
            
            .section-details
            {
                display: none;
            }
            
            .section-details.visible
            {
                display: block;
            }
            
            .employees-select-container
            {
                display: table;
            }
            
            .employees-select
            {
                width: 100%;
                height: 150px;
            }
            
            .employee-list-container
            {
                height: 300px;
                margin: 15px 0;
                position: relative;
                border: 1px solid #ccc;
                overflow-y: scroll;
            }
            
            .licensed
            {
                color: #4e4;
                background-color: #000;
                padding: 0 0.5em;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script src="/resources/scripts/employee-search.js" defer></script>
        
        <script>
            window.addEventListener('load', () =>
            {
                new EmployeeSearch('.caller-name-input', '.employee-list-container');
            });
        </script>
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
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
                <input style="display:none" type="text" value="<?php if (isset($_GET['id'])) echo $_GET['id'] ?>" name="ticket-id">
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
                        <div class="content-section padding-small">
                            <p><button type="submit" name="submitted" value="1">Submit Call Log</button></p>
                        </div>
                    </div>
                </div>
                <div class="column l8 m12 padding-small">
                    <div class="bg-white shadow section">
                        <div class="content-section padding-small">
                            <h2>Create a New Call Log</h2>
                        </div>
                    </div>
                    <div class="bg-white shadow padding-small section">
                        <div class="content-section padding-small">
                            <h2>Caller Details</h2>
                            <div class="column-container">
                                <div class="column l6 s12 v-content-section">
                                    <h4>Employee ID</h4>
                                    <input class="caller-id-input" type="text" name="caller-id" value="<?php if (isset($_POST['caller-id'])) echo htmlspecialchars($_POST['caller-id']); ?>">
                                    <?= $messages->callerID ?>
                                    <h4>Telephone Number</h4>
                                    <input class="telephone-input" type="text" name="telephone-number" value="<?php if (isset($_POST['telephone-number'])) echo htmlspecialchars($_POST['telephone-number']); ?>">
                                    <?= $messages->telephoneNumber ?>
                                </div>
                                <div class="column l6 s12 v-content-section">
                                    <h4>Search Employee Name</h4>
                                    <input class="caller-name-input" type="text">
                                    <div class="employee-list-container caller-employees"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white shadow section">
                        <div class="content-section padding-small">
                            <h2><abbr title="A more detailed explanation about the problem.">Call Log Description</abbr></h2>
                            <textarea class="description-input" name="description"><?php if (isset($_POST['description'])) echo htmlspecialchars($_POST['description']); ?></textarea>
                            <?= $messages->description ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>