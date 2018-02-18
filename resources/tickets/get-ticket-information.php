<?php

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

if (!$connection) return;

if (!isset($_GET['id'])) return;

$sqlTicketID = getSecureText($_GET['id'], $connection, true);

$sql = "SELECT
Ticket.*,

Resolution.*,

Hardware.HardwareSerialID AS Hardware_ID,
Hardware.Name AS Hardware_Name,

OperatingSystems.Name AS OS_Name,
OperatingSystems.DeveloperCompany AS OS_Developer,
OperatingSystems.LatestVersion AS OS_LatestVersion,

Software.Name AS Software_Name,
Software.LatestVersion AS Software_LastestVersion,
Software.Developer AS Software_Developer,

HelpdeskOperator.FirstName AS HelpdeskOperator_FirstName,
HelpdeskOperator.Surname AS HelpdeskOperator_Surname,
HelpdeskOperator.TelephoneNumber AS HelpdeskOperator_TelephoneNumber,

AssignedSpecialist.FirstName AS AssignedSpecialist_FirstName,
AssignedSpecialist.Surname AS AssignedSpecialist_Surname,
AssignedSpecialist.TelephoneNumber AS AssignedSpecialist_TelephoneNumber,

ResolutionEmployee.FirstName AS ResolutionEmployee_FirstName,
ResolutionEmployee.Surname AS ResolutionEmployee_Surname,
ResolutionEmployee.TelephoneNumber AS ResolutionEmployee_TelephoneNumber,

Caller.FirstName AS Caller_FirstName,
Caller.Surname AS Caller_Surname,
Caller.TelephoneNumber AS Caller_TelephoneNumber

FROM Tickets AS Ticket

LEFT JOIN Hardware ON Ticket.HardwareSerialID=Hardware.HardwareSerialID
LEFT JOIN OperatingSystems ON OperatingSystems.Name=Ticket.OperatingSystemID
LEFT JOIN Software ON Software.Name=Ticket.SoftwareID
LEFT JOIN Resolutions AS Resolution ON Ticket.ResolutionID=Resolution.ResolutionID
LEFT JOIN Employees AS HelpdeskOperator ON Ticket.HelpdeskOperator=HelpdeskOperator.EmployeeID
LEFT JOIN Employees AS AssignedSpecialist ON Ticket.AssignedSpecialist=AssignedSpecialist.EmployeeID
LEFT JOIN Employees AS ResolutionEmployee ON Resolution.EmployeeID=ResolutionEmployee.EmployeeID
LEFT JOIN Employees AS Caller ON Ticket.CallerID=Caller.EmployeeID

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

$softwareLicense = '';

if ($ticket['Software_Name'] !== null)
{
    $softwareLicense = "<br><span class=\"licensed\">&#9745;</span> <span>v{$ticket['Software_LastestVersion']}</span>";
}

$hardwareLicense = '';

if ($ticket['Hardware_ID'] !== null)
{
    $hardwareLicense = "<br><span class=\"licensed\">&#9745;</span> {$ticket['Hardware_Name']}";
}

$osLicense = '';

if ($ticket['OS_Name'] !== null)
{
    $osLicense = "<br><span class=\"licensed\">&#9745;</span> <span>v{$ticket['OS_LatestVersion']}</span>";
}

?>