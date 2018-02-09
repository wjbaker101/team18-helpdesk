<?php

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

if (!$connection) return;

if (!isset($_GET['id'])) return;

$sqlTicketID = getSecureText($_GET['id'], $connection, true);

$sql = "SELECT
Ticket.*,
Resolution.*,
Hardware.*,
OperatingSystems.Name AS OS_Name,
OperatingSystems.DeveloperCompany AS OS_Developer,
OperatingSystems.LatestVersion AS OS_LatestVersion,
HelpdeskOperator.FirstName AS HelpdeskOperator_FirstName,
HelpdeskOperator.Surname AS HelpdeskOperator_Surname,
HelpdeskOperator.TelephoneNumber AS HelpdeskOperator_TelephoneNumber,
AssignedSpecialist.FirstName AS AssignedSpecialist_FirstName,
AssignedSpecialist.Surname AS AssignedSpecialist_Surname,
AssignedSpecialist.TelephoneNumber AS AssignedSpecialist_TelephoneNumber,
ResolutionEmployee.FirstName AS ResolutionEmployee_FirstName,
ResolutionEmployee.Surname AS ResolutionEmployee_Surname,
ResolutionEmployee.TelephoneNumber AS ResolutionEmployee_TelephoneNumber
FROM Tickets AS Ticket
LEFT JOIN Resolutions AS Resolution ON Ticket.ResolutionID=Resolution.ResolutionID
LEFT JOIN Employees AS HelpdeskOperator ON Ticket.HelpdeskOperator=HelpdeskOperator.EmployeeID
LEFT JOIN Employees AS AssignedSpecialist ON Ticket.AssignedSpecialist=AssignedSpecialist.EmployeeID
LEFT JOIN Employees AS ResolutionEmployee ON Resolution.EmployeeID=ResolutionEmployee.EmployeeID
WHERE Ticket.TicketID={$sqlTicketID}
GROUP BY Ticket.TicketID";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

$ticket = $result->fetch_assoc();

$ticketStatus = 'pending';

if ($ticket['AssignedSpecialist'] !== null) $ticketStatus = 'open';

if ($ticket['ResolutionID'] !== null) $ticketStatus = 'closed';

$ticketPriority = '';

switch ($ticket['Priority'])
{
    case 0:
        $ticketPriority = 'low';
        break;
    case 1:
        $ticketPriority = 'medium';
        break;
    case 2:
        $ticketPriority = 'high';
        break;
    case 3:
        $ticketPriority = 'urgent';
        break;
}

?>