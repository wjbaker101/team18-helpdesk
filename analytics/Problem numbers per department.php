<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

require_once(ROOT . '/resources/page/utils/database.php');

if (!$connection) return;

$sql = 'SELECT CallerID, ProblemType FROM Tickets';
$sql2 = 'SELECT EmployeeID, Department FROM Employees';

$result = $connection->query($sql);
$result2 = $connection->query($sql2);

if (!$result || $result->num_rows === 0) return;
if (!$result2 || $result2->num_rows === 0) return;

$helpdeskN = 0;
$ITN = 0;
$salesN = 0;
$bossN = 0;
$HRN = 0;
$accountingN = 0;
$guardN = 0;
$analystN = 0;
$cleaningN = 0;
$PRN = 0;
$riskManagementN = 0;
$taxN = 0;

$helpdeskS = 0;
$ITS = 0;
$salesS = 0;
$bossS = 0;
$HRS = 0;
$accountingS = 0;
$guardS = 0;
$analystS = 0;
$cleaningS = 0;
$PRS = 0;
$riskManagementS = 0;
$taxS = 0;

$helpdeskH = 0;
$ITH = 0;
$salesH = 0;
$bossH = 0;
$HRH = 0;
$accountingH = 0;
$guardH = 0;
$analystH = 0;
$cleaningH = 0;
$PRH = 0;
$riskManagementH = 0;
$taxH = 0;

while ($ticket = $result->fetch_assoc())
{
    while ($employee = $result2->fetch_assoc())
    {
        if ($employee['EmployeeID'] == $ticket['CallerID'])
        {
            if ($ticket['ProblemType'] == "Network")
            {
                switch ($employee['Department']) 
                {
                    case "Helpdesk":
                      $helpdeskN++;
                      break;
                    case "IT":
                      $ITN++;
                      break;
                    case "Sales":
                      $salesN++;
                      break;
                    case "Boss":
                      $bossN++;
                      break;
                    case "HR":
                      $HRN++;
                      break;
                    case "Accounting":
                      $accountingN++;
                      break;
                    case "Guard":
                      $guardN++;
                      break;
                    case "Analyst":
                      $analystN++;
                      break;
                    case "Cleaning":
                      $cleaningN++;
                      break;
                    case "PR":
                      $PRN++;
                      break;
                    case "Risk Management":
                      $riskManagementN++;
                      break;
                    case "Tax":
                      $taxN++;
                      break;
                }
            }
            else if($ticket['ProblemType'] == "Software")
            {
                switch ($employee['Department']) 
                {
                    case "Helpdesk":
                      $helpdeskS++;
                      break;
                    case "IT":
                      $ITS++;
                      break;
                    case "Sales":
                      $salesS++;
                      break;
                    case "Boss":
                      $bossS++;
                      break;
                    case "HR":
                      $HRS++;
                      break;
                    case "Accounting":
                      $accountingS++;
                      break;
                    case "Guard":
                      $guardS++;
                      break;
                    case "Analyst":
                      $analystS++;
                      break;
                    case "Cleaning":
                      $cleaningS++;
                      break;
                    case "PR":
                      $PRS++;
                      break;
                    case "Risk Management":
                      $riskManagementS++;
                      break;
                    case "Tax":
                      $taxS++;
                      break;
                }
            }
            else
            {
                switch ($employee['Department']) 
                {
                    case "Helpdesk":
                      $helpdeskH++;
                      break;
                    case "IT":
                      $ITH++;
                      break;
                    case "Sales":
                      $salesH++;
                      break;
                    case "Boss":
                      $bossH++;
                      break;
                    case "HR":
                      $HRH++;
                      break;
                    case "Accounting":
                      $accountingH++;
                      break;
                    case "Guard":
                      $guardH++;
                      break;
                    case "Analyst":
                      $analystH++;
                      break;
                    case "Cleaning":
                      $cleaningH++;
                      break;
                    case "PR":
                      $PRH++;
                      break;
                    case "Risk Management":
                      $riskManagementH++;
                      break;
                    case "Tax":
                      $taxH++;
                      break;
                }
            }
        }
    }
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
                const graphics = document.querySelector('.analytics-canvas').getContext('2d');

                const chart = new Chart(graphics,
                {
                    axisY: 
                    {
                        title: "Number of problems",
                        maximum: 20
                    },
                    data: [
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Network",
                        color: "#60bd68",
                        dataPoints: 
                        [
                        { y: <?= $helpdeskN ?>, label: "Helpdesk"},
                        { y: <?= $ITN ?>, label: "IT"},
                        { y: <?= $salesN ?>, label: "Sales"},
                        { y: <?= $bossN ?>, label: "Boss"},
                        { y: <?= $HRN ?>, label: "HR"},
                        { y: <?= $accountingN ?>, label: "Accounting"}
                        { y: <?= $guardN ?>, label: "Guard"}
                        { y: <?= $analystN ?>, label: "Analyst"}
                        { y: <?= $cleaningN ?>, label: "Cleaning"}
                        { y: <?= $PRN ?>, label: "PR"}
                        { y: <?= $riskManagementN ?>, label: "Risk Management"}
                        { y: <?= $taxN ?>, label: "Tax"}
                        ]
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Software",
                        color: "#5da5da",
                        dataPoints: 
                        [
                        { y: <?= $helpdeskS ?>, label: "Helpdesk"},
                        { y: <?= $ITS ?>, label: "IT"},
                        { y: <?= $salesS ?>, label: "Sales"},
                        { y: <?= $bossS ?>, label: "Boss"},
                        { y: <?= $HRS ?>, label: "HR"},
                        { y: <?= $accountingS ?>, label: "Accounting"}
                        { y: <?= $guardS ?>, label: "Guard"}
                        { y: <?= $analystS ?>, label: "Analyst"}
                        { y: <?= $cleaningS ?>, label: "Cleaning"}
                        { y: <?= $PRS ?>, label: "PR"}
                        { y: <?= $riskManagementS ?>, label: "Risk Management"}
                        { y: <?= $taxS ?>, label: "Tax"}
                        ]
                    },
                    {
                        type: "bar",
                        showInLegend: true,
                        legendText: "Hardware",
                        color: "#f15854",
                        dataPoints: 
                        [
                        { y: <?= $helpdeskH ?>, label: "Helpdesk"},
                        { y: <?= $ITH ?>, label: "IT"},
                        { y: <?= $salesH ?>, label: "Sales"},
                        { y: <?= $bossH ?>, label: "Boss"},
                        { y: <?= $HRH ?>, label: "HR"},
                        { y: <?= $accountingH ?>, label: "Accounting"}
                        { y: <?= $guardH ?>, label: "Guard"}
                        { y: <?= $analystH ?>, label: "Analyst"}
                        { y: <?= $cleaningH ?>, label: "Cleaning"}
                        { y: <?= $PRH ?>, label: "PR"}
                        { y: <?= $riskManagementH ?>, label: "Risk Management"}
                        { y: <?= $taxH ?>, label: "Tax"}
                        ]
                    }
                    ]
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
                            <h2>Number of Problems per Department</h2>
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
