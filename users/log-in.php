<?php

if (!isset($_POST['submitted'])) return;

$usernameMessage = '';
$passwordMessage = '';
$errorMessage = '';

if (!isset($_POST['employee-id']) || strlen($_POST['employee-id']) === 0)
{
    $usernameMessage = '<p class="text-error">Username cannot be blank.</p>';
    return;
}

if (!isset($_POST['password']) || strlen($_POST['password']) === 0)
{
    $passwordMessage = '<p class="text-error">Password cannot be blank.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

if (!$connection)
{
    $errorMessage = '<p class="text-error">Unable to connect to database.</p>';
    return;
}

$sqlEmployeeId = getSecureText($_POST['employee-id'], $connection, true);
$sqlPassword = getSecureText($_POST['password'], $connection, true);

$sql = "SELECT EmployeeID, Password FROM EmployeeLogins WHERE EmployeeID={$sqlEmployeeId}";

$result = $connection->query($sql);

if (!$result)
{
    $errorMessage = '<p class="text-error">Unable to query database.</p>';
    return;
}

if ($result->num_rows !== 1)
{
    $errorMessage = '<p class="text-error">Incorrect login details.</p>';
    return;
}

$employee = $result->fetch_assoc();

if (!password_verify($sqlPassword, $employee['Password']))
{
    $errorMessage = '<p class="text-error">Incorrect login details.</p>';
    return;
}

$_SESSION['EmployeeID'] = $employee['EmployeeID'];
$_SESSION['Password'] = $employee['Password'];

$errorMessage = '<p>Successfully logged in.</p>';

header('Location: /view-tickets/');

?>