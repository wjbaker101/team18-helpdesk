<?php

$specialistMessage = '';

if (!isset($_POST['submitted'])) return;

if (!isset($_POST['specialist-id']) || strlen($_POST['specialist-id']) === 0)
{
    $specialistMessage = '<p class="text-error">Specialist ID cannot be blank.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

$sqlSpecialistID = getSecureText($_POST['specialist-id'], $connection, true);
$sqlTicketID = getSecureText($_POST['ticket-id'], $connection, true);

$sql = "SELECT EmployeeID FROM Employees WHERE JobTitle='IT Specialist' AND EmployeeID={$sqlSpecialistID}";

$result = $connection->query($sql);

if (!$result)
{
    $specialistMessage = '<p class="text-error">Unable to query database.</p>';
    return;
}

if ($result->num_rows !== 1)
{
    $specialistMessage = '<p class="text-error">The specialist with the given ID could not be found.</p>';
    return;
}

$sql = "UPDATE Tickets SET AssignedSpecialist={$sqlSpecialistID} WHERE TicketID={$sqlTicketID}";

if ($connection->query($sql))
{
    header("Location: /ticket/?id={$sqlTicketID}");
}
else
{
    $specialistMessage = '<p class="text-error">Unable to update ticket information.</p>';
}

?>