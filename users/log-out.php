<?php

// Initialise the current session
session_start();

// Delete the session containing the login details
session_unset();
session_destroy();
$_SESSION = array();

// Redirect the user to the login page
// So they are able to log into a different user
header('Location: /users/login.php');

?>