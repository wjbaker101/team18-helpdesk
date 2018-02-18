<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

require_once(ROOT . '/resources/page/utils/database.php');

if (!$connection) return;

$sql = 'SELECT ResolutionID, EntryDate, ProblemType FROM Tickets';
$sql2 = 'SELECT ResolutionID, CloseDate FROM Resolutions';

$result = $connection->query($sql);
$result2 = $connection->query($sql2);

if (!$result || $result->num_rows === 0) return;
if (!$result2 || $result2->num_rows === 0) return;

$network = new DateTime('2000-01-01 00:00:00');
$software = new DateTime('2000-01-01 00:00:00');
$hardware = new DateTime('2000-01-01 00:00:00');
$networkCount = 0;
$softwareCount = 0;
$hardwareCount = 0;

while ($resolution = $result2->fetch_assoc())
{
    while ($ticket = $result->fetch_assoc())
    {
        if ($resolution['ResolutionID'] == $ticket['ResolutionID'])
        {
            $dateEntry = new DateTime($ticket['EntryDate']); 
            $dateClose = new DateTime($resolution['CloseDate']); 
            $dateDiff  = $dateEntry->diff($dateClose);

            if ($ticket['ProblemType'] == "Network")
            {
                $networkCount++;
                $network = strtotime($dateDiff->format("%H:%I"), $network);
            }
            else if($ticket['ProblemType'] == "Software")
            {
                $softwareCount++;
                $software = strtotime($dateDiff->format("%H:%I"), $software);
            }
            else
            {
                $hardwareCount++;
                $hardware = strtotime($dateDiff->format("%H:%I"), $hardware);
            }
        }
    }
}

$default = new DateTime('2000-01-01 00:00:00');

$secDiff = strtotime($network) - strtotime($default);
$networkFinal = $secDiff / $networkCount;
$networkFinal = gmdate("H:i", $networkFinal);

$secDiff = strtotime($software) - strtotime($default);
$softwareFinal = $secDiff / $softwareCount;
$softwareFinal = gmdate("H:i", $networkFinal);

$secDiff = strtotime($hardware) - strtotime($default);
$hardwareFinal = $secDiff / $hardwareCount;
$hardwareFinal = gmdate("H:i", $networkFinal);


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
                                label: "Average Time to Solve Problem (hours)",
                                data: [<?= $networkFinal ?>, <?= $softwareFinal ?>, <?= $hardwareFinal ?>],
                                backgroundColor:
                                [
                                    '#000',
                                    '#000',
                                    '#000',
                                ]
                            }
                        ],
                        labels:
                        [
                            'Network',
                            'Software',
                            'Hardware',
                        ],
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
                <a href="/view-tickets/">&larr; Return to Tickets Overview</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width clearfix">
            <div class="padding-small">
                <div class="bg-white shadow">
                    <div class="column-container">
                        <div class="column l6 m12 padding-small text-centered">
                            <h2>Average Time to Solve Problem types</h2>
                            <div class="canvas-container vpadding-mid">
                                <canvas class="analytics-canvas" width="250" height="250"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>
