<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/resources/page/utils/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/resources/page/utils/cleanup-utils.php');

if (!$connection)
{
    echo '<p class="text-error">Unable to connect to the database.</p>';
    return;
}

if (!isset($_GET['id'])) return;

$sqlTicketID = getSecureText($_GET['id'], $connection, true);

$sql = "DELETE FROM Tickets WHERE TicketID={$sqlTicketID}";

$result = $connection->query($sql);

if (!$result)
{
    echo '<p class="text-error">Unable to delete the ticket.</p>';
    return;
}

echo '<p>Successfully deleted ticket.</p>';


?>