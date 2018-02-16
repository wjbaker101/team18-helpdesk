<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include('log-in.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Menu</title>
        
        <meta name="description" content="Select the page you would like to view.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style>
  	
		 .button {
  display: inline-block;
  border-radius: 4px;
  background-color: #202020;
  border: none;
  color: #FFFFFF;
  text-align: center;
  font-size: 28px;
  padding: 20px;
  width: 200px;
  transition: all 0.5s;
  cursor: pointer;
  margin: 5px;
}

.button span {
  cursor: pointer;
  display: inline-block;
  position: relative;
  transition: 0.5s;
}

.button span:after {
  content: '\00bb';
  position: absolute;
  opacity: 0;
  top: 0;
  right: -20px;
  transition: 0.5s;
}

.button:hover span {
  padding-right: 25px;
}

.button:hover span:after {
  opacity: 1;
  right: 0;
}


		
		</style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script>
            window.addEventListener('load', () =>
            {
                const loginForm = document.querySelector('.login-form');
                
                const elements =
                [
                    document.querySelector('.employee-id-input'),
                    document.querySelector('.password-input'),
                ];
                
                elements.forEach((element) =>
                {
                    element.addEventListener('keypress', (event) =>
                    {
                        if (event.keyCode === 13)
                        {
                            loginForm.submit();
                        }
                    });
                });
            });
        </script>
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
                    <?= $errorMessage ?>
                </form>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>