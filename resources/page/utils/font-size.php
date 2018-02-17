<?php

session_start();

// Unsets the session so a larger font is no longer being used
if (isset($_POST['font-small']) && isset($_SESSION['font-size-large']))
{
    unset($_SESSION['font-size-large']);
}

// Sets the session so a larger font is being used
if (isset($_POST['font-large']))
{
    $_SESSION['font-size-large'] = 'true';
}

$url = $_POST['url'];

// Redirect back to the page the user was on
header("Location: {$url}");

?>