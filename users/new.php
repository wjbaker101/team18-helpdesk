<?php

require ($_SERVER['DOCUMENT_ROOT'] . "/resources/page/page.php");

include('create-user.php');

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <?php include(INCLUDE_META) ?>
        
        <title>Team 18 Helpdesk - New Account</title>
        
        <meta name="description" content="Create a new account on the Team 18 Helpdesk system.">
        
        <?php include(INCLUDE_STYLE) ?>
        
        <style></style>
        
        <?php include(INCLUDE_SCRIPTS) ?>
        
        <script></script>
    </head>
    
    <body>
        <?php include(INCLUDE_HEADER) ?>
        <nav role="navigation" class="padding-small clearfix">
            <div class="float-left">
                <a href="/users/login.php">&larr; Return to Login page</a>
            </div>
            <div class="float-right">
            </div>
        </nav>
        <div class="content-width clearfix padding-small">
            <div class="padding-small shadow bg-white">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <h2>Create a New Employee Login</h2>
                    <h4>Your Employee ID</h4>
                    <input type="text" name="employee-id" value="<?php if (isset($_POST['employee-id'])) echo htmlspecialchars($_POST['employee-id']); ?>" autofocus>
                    <?= $idMessage ?>
                    <h4>Username</h4>
                    <input type="text" name="username" value="<?php if (isset($_POST['username'])) echo htmlspecialchars($_POST['username']); ?>">
                    <?= $usernameMessage ?>
                    <h4>Password</h4>
                    <input type="password" name="password1">
                    <?= $password1Message ?>
                    <h4>Confirm Password</h4>
                    <input type="password" name="password2">
                    <?= $password2Message ?>
                    <p><button name="submitted" type="submit" value="1">Create Account</button></p>
                    <?= $errorMessage ?>
                </form>
            </div>
        </div>
        <?php include(INCLUDE_FOOTER) ?>
    </body>
</html>