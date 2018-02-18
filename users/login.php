<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

// Checks whether an employee is logged in
// Redirects to overview page
// Prevents Further code from being ran
if ($employee !== null)
{
    header ('Location: /overview/');
    exit;
}

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
        
        <script></script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <div class="content-width clearfix padding-small">
            <div class="padding-small shadow bg-white text-centered">
                <form class="login-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Login</h2>
                    <h4>Username</h4>
                    <input class="username-input" type="text" name="username" value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>">
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