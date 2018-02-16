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
            .button
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
                <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Welcome</h2>
                    
					<h4>Select the page you would like to view.</h4>
                    
					
					<FORM METHOD="LINK" ACTION="view-tickets/index.php">
					<p><button class="button" INPUT TYPE="submit">View Tickets</button></p>
					</FORM>
					                 
									 
									 
									 <!--   <h4>sample text</h4> -->
									 
					<FORM METHOD="LINK" ACTION="analytics/index.php">				 
					<p><button class="button" INPUT TYPE="submit">View Analytics</button></p>
					</FORM>
					
                                       <!-- <p><button name="submitted" type="submit" value="1">Login</button></p>*/ -->
                </form>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>