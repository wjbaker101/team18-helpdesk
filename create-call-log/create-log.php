<?php

$messages = (object)
[
    'callerID' => '',
    'description' => '',
    'telephoneNumber' => '',
];

if (!isset($_POST['submitted'])) return;

if (!isset($_POST['caller-id']) || strlen($_POST['caller-id']) === 0)
{
    $messages->callerID = '<p class="text-error">Caller ID cannot be blank.</p>';
    return;
}

if (!isset($_POST['telephone-number']) || strlen($_POST['telephone-number']) === 0)
{
    $messages->telephoneNumber = '<p class="text-error">Telephone number cannot be blank.</p>';
    return;
}

if (!isset($_POST['description']) || strlen($_POST['description']) === 0)
{
    $messages->description = '<p class="text-error">Description cannot be blank.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

$sqlCallerID = getSecureText($_POST['caller-id'], $connection, true);

$sql = "SELECT EmployeeID FROM Employees WHERE EmployeeID={$sqlCallerID}";

$result = $connection->query($sql);

if (!$result)
{
    $messages->callerID = '<p class="text-error">Error whilst querying the database.</p>';
    return;
}
elseif ($result->num_rows !== 1)
{
    $messages->callerID = '<p class="text-error">Unable to find employee with the given ID.</p>';
    return;
}

$sqlTicketID = getSecureText($_POST['ticket-id'], $connection, true);
$sqlDescription = getSecureText($_POST['description'], $connection, true);
$sqlEntryDate = (new DateTime())->format('Y-m-d H:i:s');
$sqlTelephoneNumber = getSecureText($_POST['telephone-number'], $connection, true);
$sqlHelpdeskOperator = getSecureText($_SESSION['EmployeeID'], $connection, true);

$fields = 'TicketID, Description, EntryDate, CallerID, TelephoneNumber, HelpdeskOperator';

$values = "{$sqlTicketID}, '{$sqlDescription}', '{$sqlEntryDate}', {$sqlCallerID}, '{$sqlTelephoneNumber}', {$sqlHelpdeskOperator}";

$sql = "INSERT INTO CallLogs ({$fields}) VALUES ({$values})";

$result = $connection->query($sql);

if (!$result)
{
    $messages->error = "<p class=\"text-error\">Unable to create ticket.</p><p>{$connection->error}</p>";
    return;
}

$connection->close();

header("Location: /ticket/?id={$_POST['ticket-id']}");

?>