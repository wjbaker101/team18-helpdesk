<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

// Checks whether the logged in employee actually exists
// Redirects to login page
// Prevents Further code from being ran
if ($employee === null)
{
    header ('Location: /users/login.php');
    exit;
}

$pageNumber = 1; // Default page number

// Get page number
if (isset($_GET['page'])) $pageNumber = intval($_GET['page']);

// Gets previous page number
// Sets minimum to 1
$prevPageNumber = $pageNumber - 1;

if ($prevPageNumber < 1) $prevPageNumber = 1;

// Gets next page number
$nextPageNumber = $pageNumber + 1;

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - View Tickets</title>
        
        <meta name="description" content="View all open, pending and closed tickets as an overview.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .filter-pending
            {
                font-weight: bold;
                color: #e44;
            }
            
            .filter-open
            {
                font-weight: bold;
                color: #4e4;
            }
            
            .filter-closed
            {
                font-weight: bold;
                color: #44e;
            }
            
            table
            {
                border: 0;
                display: block;
                overflow-x: auto;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script>
            let pageNumber = 1; // Current page number

            /**
             * Requests the server for a list of tickets using options from the sorting and filter.
             */
            const getTickets = () =>
            {
                
                // Defines the options
                const options =
                {
                    sort: $('[name=sorting]:checked').val(),
                    descendingOrder: $('[name=order]')[0].checked,
                    showPending: $('[name=show-pending]')[0].checked,
                    showOpen: $('[name=show-open]')[0].checked,
                    showClosed: $('[name=show-closed]')[0].checked,
                    page: pageNumber,
                    specialist: <?php echo ($employee['JobTitle'] === 'IT Specialist') ? 'true' : 'false'; ?>,
                    specialistID: <?= $employee['EmployeeID'] ?>,
                };
                
                const http = new XMLHttpRequest() || new ActiveXObject('Microsoft.XMLHTTP');

                // Displays tickets when response has been recieved
                http.onreadystatechange = () =>
                {
                    if (http.readyState === XMLHttpRequest.DONE)
                    {
                        if (http.status === 200)
                        {
                            const response = http.responseText;

                            $('.tickets-container').html(response);
                        }
                    }
                };

                // Create parameters to send to the server
                const parameters = `?sort=${options.sort}&order=${options.descendingOrder}&pending=${options.showPending}&open=${options.showOpen}&closed=${options.showClosed}&page=${options.page}&specialist=${options.specialist}&specialistID=${options.specialistID}`;

                http.open('GET', 'get-tickets.php' + parameters, true);

                http.send();
            };
            
            /**
             * Increments the page number by the given value.
             * Keeps number within the bounds if exceeded.
             */
            const incrementPageNumber = (value) =>
            {
                pageNumber += value;
                
                if (pageNumber < 1) pageNumber = 1;
            };
            
            /**
             * Initialises the buttons to increment or decrement the page number.
             */
            const initPageButtons = () =>
            {
                const leftButton = $('.page-left');
                const rightButton = $('.page-right');
                
                const pageNumberElement = $('.page-number');
                
                // Decrement the page number when the left button is clicked
                leftButton.click(() =>
                {
                    incrementPageNumber(-1);
                    
                    getTickets(pageNumber);
                    
                    pageNumberElement.html(`Page ${pageNumber}`);
                });
                
                // Increment the page number when the right button is clicked
                rightButton.click(() =>
                {
                    incrementPageNumber(1);
                    
                    getTickets(pageNumber);
                    
                    pageNumberElement.html(`Page ${pageNumber}`);
                });
            };

            window.addEventListener('load', getTickets);

            /**
             * Loads the input elements that should refresh the ticket list.
             */
            window.addEventListener('load', () =>
            {
                const ticketInputs = $('.tickets-input');
                
                ticketInputs.change(getTickets);
                
                initPageButtons();
            });
        </script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/overview/">&larr; Return to Overview page</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width column-container">
            <div class="column l4 m12 padding-small">
                <div class="section bg-white shadow">
                    <div class="content-section padding-small">
                        <h2>Sort and Filter</h2>
                        <h3>Sorting</h3>
                        <p>
                            <input class="tickets-input" type="radio" name="sorting" value="0" checked>
                            <span>Creation Date</span>
                        </p>
                        <p>
                            <input class="tickets-input" type="radio" name="sorting" value="1">
                            <span>Ticket ID</span>
                        </p>
                        <p>
                            <input class="tickets-input" type="radio" name="sorting" value="2">
                            <span>Priority</span>
                        </p>
                        <p>
                            <input class="tickets-input" type="checkbox" name="order" checked>
                            <span>Descending Order</span>
                        </p>
                    </div>
                    <div class="padding-small content-section">
                        <h3>Filters</h3>
                        <p>
                            <input class="tickets-input" type="checkbox" name="show-pending" checked>
                            <span class="filter-pending">Show Pending Tickets</span>
                        </p>
                        <p>
                            <input class="tickets-input" type="checkbox" name="show-open" checked>
                            <span class="filter-open">Show Open Tickets</span>
                        </p><p>
                            <input class="tickets-input" type="checkbox" name="show-closed">
                            <span class="filter-closed">Show Closed Tickets</span>
                        </p>
                    </div>
                </div>
                <div class="section bg-white shadow">
                    <div class="content-section padding-small">
                        <h2>Actions</h2>
                        <p><a href="/create-ticket/"><button>Create a New Ticket</button></a></p>
                    </div>
                </div>
            </div>
            <div class="column l8 m12 padding-small">
                <div class="padding-small bg-white shadow">
                    <h2>Tickets</h2>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Short Summary</th>
                                <th>Date Created</th>
                                <th>Status</th>
                                <th>Priority</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tickets-container"></tbody>
                    </table>
                    <h3>Page</h3>
                    <a><button class="page-left">&larr;</button></a>
                    <span class="page-number">Page <?php echo $pageNumber; ?></span>
                    <a><button class="page-right">&rarr;</button></a>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>