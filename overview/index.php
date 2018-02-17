<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Menu</title>
        
        <meta name="description" content="Select the page you would like to view.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
            .overview-button
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
        <div class="content-width clearfix padding-small">
            <div class="padding-small shadow bg-white text-centered">
                <h2>Welcome</h2>
                <h4>Select the page you would like to view.</h4>
                <p><a href="/view-tickets/"><button class="overview-button">View Tickets</button></a></p>
                <p><a href="/analytics/"><button class="overview-button">View Analytics</button></a></p>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>