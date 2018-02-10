<?php

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

if (!$connection) return;

if (!isset($_GET['id'])) return;

$sqlTicketID = getSecureText($_GET['id'], $connection, true);

$sql = "SELECT * FROM CallLogs WHERE TicketId={$sqlTicketID}";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

$count = 1;

while ($ticket = $result->fetch_assoc())
{
    $date = (new DateTime($ticket['EntryDate']))->format('d/m/Y H:i');
    
    echo "
        <div class=\"content-section padding-small\">
            <h3>({$count}) {$date}</h3>
            <p>{$ticket['Description']}</p>
        </div>
    ";
    
    $count++;
}

?>