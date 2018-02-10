<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include('get-ticket.php');

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
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script>
            let isRequesting = false;
            
            const requestEmployees = (searchTerm = '') =>
            {
                isRequesting = true;
                
                const http = new XMLHttpRequest() || new ActiveXObject('Microsoft.XMLHTTP');

                http.onreadystatechange = () =>
                {
                    if (http.readyState === XMLHttpRequest.DONE)
                    {
                        if (http.status === 200)
                        {
                            const response = http.responseText;

                            document.querySelector('.employee-list-container').innerHTML = response;
                            
                            isRequesting = false;
                        }
                    }
                };

                const parameters = `?name=${searchTerm}`;

                http.open('GET', '/resources/tickets/find-employees.php' + parameters, true);

                http.send();
            };
            
            const initEmployeeSearch = () =>
            {
                const input = document.querySelector('.search-employee-input');
                
                input.addEventListener('input', () =>
                {
                    document.querySelector('.employee-list-container').innerHTML = '';
                    
                    if (input.value.length === 0) return;
                    
                    if (isRequesting) return;
                    
                    requestEmployees(input.value.toLowerCase().trim());
                });
            };
            
            window.addEventListener('load', initEmployeeSearch);
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
        <div class="content-width clearfix">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?php echo htmlspecialchars($_GET['id']); ?>" method="post">
                <input style="display:none" type="text" value="<?php if (isset($_GET['id'])) echo $_GET['id'] ?>" name="ticket-id">
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
                                <strong>Problem Type:</strong>
                                <span><?= $ticket['ProblemType'] ?></span>
                            </p>
                            <?php if ($ticket['HardwareSerialID'] !== null) { ?>
                            <p>
                                <strong>Hardware Serial ID:</strong>
                                <span><?= $ticket['HardwareSerialID'] ?></span>
                            </p>
                            <?php } ?>
                            <?php if ($ticket['OperatingSystemID'] !== null) { ?>
                            <p>
                                <strong>Operating System:</strong>
                                <span><?= $ticket['OS_Name'] ?></span>
                            </p>
                            <?php } ?>
                            <?php if ($ticket['Software_Name'] !== null) { ?>
                            <p>
                                <strong>Software:</strong>
                                <span><?= $ticket['Software_Name'] ?></span>
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
                <div class="main-content padding-small">
                    <div class="bg-white shadow section">
                        <div class="content-section padding-small">
                            <h2>Create a New Call Log</h2>
                        </div>
                    </div>
                    <div class="bg-white shadow section">
                        <div class="content-section padding-small">
                            <h2><abbr title="Details about the employee calling.">Caller Details</abbr></h2>
                            <div class="column-container">
                                <div class="column l6 s12 v-content-section">
                                    <h4><abbr title="Employee ID of the employee.">Employee ID</abbr></h4>
                                    <input class="caller-id-input" type="text" name="caller-id" value="<?php if (isset($_POST['caller-id'])) echo htmlspecialchars($_POST['caller-id']); ?>">
                                    <?= $messages->callerID ?>
                                    <h4><abbr title="Telephone number of the incoming call.">Telephone Number</abbr></h4>
                                    <input class="telephone-input" type="text" name="telephone-number" value="<?php if (isset($_POST['telephone-number'])) echo htmlspecialchars($_POST['telephone-number']); ?>">
                                </div>
                                <div class="column l6 s12 v-content-section">
                                    <h4>Search Employee Name</h4>
                                    <input class="search-employee-input" type="text">
                                    <div class="employee-list-container">
                                        
                                    </div>
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