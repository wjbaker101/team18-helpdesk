<?php

$resolutionMessage = '';

if (!isset($_POST['submitted'])) return;

if (!isset($_POST['description']) || strlen($_POST['description']) === 0)
{
    $resolutionMessage = '<p class="text-error">Resolution description cannot be blank.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

$sqlHelpdeskOperator = getSecureText($_SESSION['EmployeeID'], $connection, true);
$sqlEntryDate = (new DateTime())->format('Y-m-d H:i:s');
$sqlTicketID = getSecureText($_POST['ticket-id'], $connection, true);

$sqlResolutionID = getCreateResolutionID();

if ($sqlResolutionID === false) return;

// Update the ticket with the resolution ID

$sql = "UPDATE Tickets SET ResolutionID={$sqlResolutionID} WHERE TicketID={$sqlTicketID}";

$result = $connection->query($sql);

if (!$result)
{
    $resolutionMessage = '<p class="text-error">Error whilst adding resolution.</p>';
    return;
}
else
{
    header("Location: /ticket/?id={$sqlTicketID}");
}

/**
 * Gets the ID of the resolution after querying the database,
 * or return false if and error occured.
 */
function getCreateResolutionID()
{
    if (!isset($_POST['description']) || strlen($_POST['description']) === 0)
    {
        $resolutionMessage = '<p class="text-error">Resolution description cannot be blank.</p>';
    }
    else
    {
        // Add the resolution information to the database
        
        $sqlResolutionDescription = getSecureText($_POST['description'], $connection, true);

        $sql = "INSERT INTO Resolutions (CloseDate, Message, EmployeeID) VALUES ('{$sqlEntryDate}', '{$sqlResolutionDescription}', {$sqlHelpdeskOperator})";

        $result = $connection->query($sql);

        if (!$result)
        {
            $resolutionMessage = '<p class="text-error">Error whilst adding resolution.</p>';
        }
        else
        {
            return $connection->insert_id; // Return the newly created resolution's ID
        }
    }
    
    return false;
}

?>