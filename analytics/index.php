<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

require_once(ROOT . '/resources/page/utils/database.php');

if (!$connection) return;

$sql = 'SELECT AssignedSpecialist, ResolutionID FROM Tickets';

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

$closed = 0;
$open = 0;
$pending = 0;

while ($ticket = $result->fetch_assoc())
{
    if ($ticket['ResolutionID'] !== null)
    {
        $closed++;
    }
    else if ($ticket['AssignedSpecialist'] !== null)
    {
        $open++;
    }
    else
    {
        $pending++;
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
            
            .analytics-button
            {
                font-size: 28px;
                padding: 20px;
                width: 200px;
            }
        </style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script></script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/overview/">&larr; Return to Overview</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width clearfix">
            <div class="padding-small">
                <div class="bg-white shadow vpadding-mid text-centered">
                    <h1>Select a Graph to View</h1>
                    <div class="column-container">
                        <div class="column l6 s12">
                            <p><a href="ticket-status.php"><button class="analytics-button">Ticket Status Ratio</button></a></p>
                        </div>
                        <div class="column l6 s12">
                            <p><a href="helpdesk-vs-specialist.php"><button class="analytics-button">Helpdesk vs Specialist</button></a></p>
                        </div>
                    </div>
                    <div class="column-container">
                        <div class="column l6 s12">
                            <p><a href="solve-time.php"><button class="analytics-button">Ticket Solve Time</button></a></p>
                        </div>
                        <div class="column l6 s12">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>