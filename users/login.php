<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include('log-in.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - Login</title>
        
        <meta name="description" content="Login as a Helpdesk Operator or IT Specialist.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style></style>
        
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
                    <h2>Login</h2>
                    <h4>Employee ID</h4>
                    <input class="employee-id-input" type="text" name="employee-id" value="<?php if (isset($_POST['employee-id'])) echo htmlspecialchars($_POST['employee-id']); ?>">
                    <?= $usernameMessage ?>
                    <h4>Password</h4>
                    <input class="password-input" type="password" name="password">
                    <?= $passwordMessage ?>
                    <p><button name="submitted" type="submit" value="1">Login</button></p>
                    <?= $errorMessage ?>
                </form>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>