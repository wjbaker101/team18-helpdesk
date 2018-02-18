<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");
require_once(ROOT . '/resources/page/utils/database.php');

if (!$connection) return;

$sql = "SELECT TicketID, EntryDate, CloseDate FROM Tickets, Resolutions WHERE Tickets.ResolutionID=Resolutions.ResolutionID ORDER BY EntryDate ASC";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

$graphValues = array();
$graphLabels = array();

while ($ticket = $result->fetch_assoc())
{
    $entryDate = new DateTime($ticket['EntryDate']);
    $closeDate = new DateTime($ticket['CloseDate']);
    
    $difference = $closeDate->diff($entryDate);
    
    $diff = $difference->format('%i');
    
    $graphValues[] = $diff;
    
    $graphLabels[] = '"Ticket ' . $ticket['TicketID'] . '"';
}

$averageSolveTime = array_sum($graphValues) / count($graphValues);

$connection->close();

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Analytics</title>
        
        <meta name="description" content="Analytics of different aspects of the helpdesk system.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .options-panel
            {
                width: 300px;
                position: sticky;
                top: 15px;
            }
            
            .analytics-panel
            {
                width: calc(100% - 300px);
            }
            
            .canvas-container
            {
                width: 350px;
                height: 350px;
                max-width: 100%;
                max-height: 100%;
                display: table;
                margin-left: auto;
                margin-right: auto;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
        
        <script>
            window.addEventListener('load', () =>
            {
                const graphics = document.querySelector('.analytics-canvas').getContext('2d');
                const chart = new Chart(graphics,
                {
                    type: 'bar',
                    data:
                    {
                        datasets:
                        [
                            {
                                label: "Solve Time (Minutes)",
                                data: [<?php echo join(', ', $graphValues); ?>],
                                backgroundColor: '#5da5da',
                            }
                        ],
                        labels: [<?php echo join(', ', $graphLabels); ?>],
                    },
                    options:
                    {
                        backgroundColor:
                        [
                             '#60bd68',
                             '#5da5da',
                             '#f15854',
                        ]
                    },
                });
            });
        </script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/analytics/">&larr; Return to Analytics</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width clearfix">
            <div class="padding-small">
                <div class="bg-white shadow vpadding-mid text-centered">
                    <h1>Solve Times of Tickets</h1>
                    <p>
                        <strong>Average Solve Time:</strong>
                        <?= $averageSolveTime ?> minutes
                    </p>
                    <div class="canvas-container vpadding-mid">
                        <canvas class="analytics-canvas" width="250" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>