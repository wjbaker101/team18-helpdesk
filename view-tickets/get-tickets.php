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
    
    $addCallLog = '';
    
    if ($status !== 'closed')
    {
        $addCallLog = "
            <a href=\"/create-call-log/?id={$ticketID}\" title=\"Create a new call log for this ticket.\">
                <svg width=\"17\" class=\"cell-middle\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\">
                    <path fill=\"#000\" d=\"M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm144 276c0 6.6-5.4 12-12 12h-92v92c0 6.6-5.4 12-12 12h-56c-6.6 0-12-5.4-12-12v-92h-92c-6.6 0-12-5.4-12-12v-56c0-6.6 5.4-12 12-12h92v-92c0-6.6 5.4-12 12-12h56c6.6 0 12 5.4 12 12v92h92c6.6 0 12 5.4 12 12v56z\"></path>
                </svg>
            </a>";
    }

    echo "
        <tr>
            <td>{$ticketID}</td>
            <td>{$ticketSummary}</td>
            <td>{$ticketDate}</td>
            <td><i class=\"status-{$status}\"></i></td>
            <td><span class=\"priority-{$priority}\"></span></td>
            <td class=\"text-centered\">
                <a href=\"/ticket/?id={$ticketID}\" title=\"View information about this ticket.\">
                    <svg width=\"17\" class=\"cell-middle\" xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\">
                        <path fill=\"#000\" d=\"M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z\"></path>
                    </svg>
                </a>
                {$addCallLog}
            </td>
        </tr>
    ";
}

$connection->close();

?>