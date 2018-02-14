<?php

$usernameMessage = '';
$idMessage = '';
$password1Message = '';
$password2Message = '';
$errorMessage = '';

if (!isset($_POST['submitted'])) return;

if (!isset($_POST['employee-id']) || strlen($_POST['employee-id']) === 0)
{
    $idMessage = '<p>Employee ID cannot be blank.</p>';
    return;
}

if (!isset($_POST['username']) || strlen($_POST['username']) === 0)
{
    $usernameMessage = '<p>Username cannot be blank.</p>';
    return;
}

if (!isset($_POST['password1']) || strlen($_POST['password1']) === 0)
{
    $password1Message = '<p>Password cannot be blank.</p>';
    return;
}

if (!isset($_POST['password2']) || strlen($_POST['password2']) === 0)
{
    $password2Message = '<p>Confirmation password cannot be blank.</p>';
    return;
}

if ($_POST['password1'] !== $_POST['password2'])
{
    $password2Message = '<p>Passwords must match.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

if (!$connection)
{
    $errorMessage = '<p>Unable to connect to database.</p>';
    return;
}

$employeeId = getSecureText($_POST['employee-id'], $connection, true);

$sql = "SELECT * FROM Employees LEFT JOIN EmployeeLogins ON Employees.EmployeeID=EmployeeLogins.EmployeeID WHERE Employees.EmployeeID={$employeeId}";

$result = $connection->query($sql);

if (!$result)
{
    $errorMessage = '<p>Unable to query database.</p>';
    return;
}

if ($result->num_rows === 0)
{
    $errorMessage = '<p>Employee ID does not exist.</p>';
    return;
}

$employee = $result->fetch_assoc();

if ($employee['Password'] !== null)
{
    $errorMessage = '<p>You should already have a login.<br>Please contact an administrator if you would like to reset your password.</p>';
    return;
}

$fields = 'EmployeeID, Username, Password';

$sqlUsername = getSecureText($_POST['username'], $connection, true);
$sqlPassword = password_hash($_POST['password1'], PASSWORD_DEFAULT);

$values = "{$employeeId}, '{$sqlUsername}', '{$sqlPassword}'";

$sql = "INSERT INTO EmployeeLogins ({$fields}) VALUES ({$values})";

$result = $connection->query($sql);

if (!$result)
{
    $errorMessage = '<p>Unable to create new login.<br>Please try again later.</p>' . $sqlUsername;
    return;
}
else
{
    header('Location: /users/login.php');
}

$connection->close();

?>