<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/resources/page/utils/database.php');

if (!$connection) return;

$sort = 'TicketID';

if (isset($_GET['sort']))
{
    switch ($_GET['sort'])
    {
        case '0':
            $sort = 'EntryDate';
            break;
        case '1':
            $sort = 'TicketID';
            break;
        case '2':
            $sort = 'Priority';
            break;
        default:
            $sort = 'TicketID';
            break;
    }
}

$order = 'ASC';

if (isset($_GET['order']) && $_GET['order'] === 'true') $order = 'DESC';

$sql = "SELECT * FROM Tickets ORDER BY {$sort} {$order}";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

while ($ticket = $result->fetch_assoc())
{
    $ticketID = $ticket['TicketID'];
    $ticketSummary = $ticket['Summary'];
    $ticketDate = (new DateTime($ticket['EntryDate']))->format('d/m/Y H:i');
    
    if ($ticket['ResolutionID'] !== null)
    {
        if (!isset($_GET['closed']) || $_GET['closed'] !== 'true')
        {
            continue;
        }
        else
        {
            $status = 'closed';
        }
    }
    else if ($ticket['AssignedSpecialist'] !== null)
    {
        if (!isset($_GET['open']) || $_GET['open'] !== 'true')
        {
            continue;
        }
        else
        {
            $status = 'open';
        }
    }
    else
    {
        if (!isset($_GET['pending']) || $_GET['pending'] !== 'true')
        {
            continue;
        }
        else
        {
            $status = 'pending';
        }
    }

    $priority = '';

    switch ($ticket['Priority'])
    {
        case 0:
            $priority = 'low';
            break;
        case 1:
            $priority = 'medium';
            break;
        case 2:
            $priority = 'high';
            break;
        case 3:
            $priority = 'urgent';
            break;
    }

    echo "
        <tr>
            <td>{$ticketID}</td>
            <td>{$ticketSummary}</td>
            <td>{$ticketDate}</td>
            <td><i class=\"status-{$status}\"></i></td>
            <td><span class=\"priority-{$priority}\"></span></td>
            <td><a href=\"/ticket/?id={$ticketID}\"><button>View Ticket</button></a></td>
        </tr>
    ";
}

$connection->close();

?>