<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include(ROOT . '/resources/tickets/get-ticket-information.php');

if (!isset($ticket))
{
    header('Location: /view-tickets/');
    exit;
}

include('assign.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Assign Specialist</title>
        
        <meta name="description" content="Assign an IT Specialist to a ticket.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .employee-list-container
            {
                height: 200px;
                margin: 15px 0;
                position: relative;
                border: 1px solid #ccc;
                overflow-y: scroll;
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
        <div class="content-width clearfix padding-small">
            <div class="padding-small shadow bg-white">
                <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>?id=<?= $ticket['TicketID'] ?>" method="post">
                    <input style="display:none" name="ticket-id" value="<?= $ticket['TicketID'] ?>">
                    <h2>Assign Specialist to Ticket <?= $ticket['TicketID'] ?></h2>
                    <div class="column-container">
                        <div class="column l6 s12 v-content-section">
                            <h4><abbr title="Specialist ID of the employee.">Specialist ID</abbr></h4>
                            <input class="specialist-id-input" type="text" name="specialist-id" value="<?php if (isset($_POST['specialist-id'])) echo htmlspecialchars($_POST['specialist-id']); ?>">
                            <?= $specialistMessage ?>
                            <p><button name="submitted" value="1">Submit New Specialist</button></p>
                        </div>
                        <div class="column l6 s12 v-content-section">
                            <h4>Search Specialist Name</h4>
                            <input class="search-employee-input" type="text">
                            <div class="employee-list-container"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>