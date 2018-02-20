<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

require_once(ROOT . '/resources/page/utils/database.php');

if (!$connection) return;

// Select all employees from closed tickets
$sql = 'SELECT Resolutions.EmployeeID, Employees.JobTitle FROM Resolutions, Employees WHERE Resolutions.EmployeeID=Employees.EmployeeID';

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

// Create variables to store the number of helpdesk or IT specialists found from the SQL query
$helpdesk = 0;
$specialist = 0;

// Loop through each employee
while ($employee = $result->fetch_assoc())
{
    // Increment variables depending on the job title of the employee
    if ($employee['JobTitle'] === 'IT Specialist') $specialist++;
    else $helpdesk ++;
}

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
                const graphics = $('.analytics-canvas')[0].getContext('2d');

                const chart = new Chart(graphics,
                {
                    type: 'pie',
                    data:
                    {
                        datasets:
                        [
                            {
                                data: [<?= $helpdesk ?>, <?= $specialist ?>],
                                backgroundColor:
                                [
                                    '#60bd68',
                                    '#f15854',
                                ]
                            }
                        ],
                        labels:
                        [
                            'Helpdesk Operator',
                            'Specialist',
                        ],
                    },
                    options:
                    {
                        backgroundColor:
                        [
                            '#4800ff',
                            '#8613d8',
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
                    <h2>Tickets Solved by Employee Types Ratio</h2>
                    <div class="canvas-container vpadding-mid">
                        <canvas class="analytics-canvas" width="250" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>
