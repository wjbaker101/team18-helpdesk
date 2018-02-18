<?php

// Messages to display if there is an error
$messages = (object)
[
    'summary' => '',
    'priority' => '',
    'description' => '',
    'callerID' => '',
    'telephoneNumber' => '',
    'problemType' => '',
    'hardwareSerialID' => '',
    'operatingSystem' => '',
    'software' => '',
    'error' => '',
    'specialist' => '',
    'resolutionDescription' => '',
];

if (!isset($_POST['submitted'])) return;

// Check whether required fields are valid

if (!isset($_POST['summary']) || strlen($_POST['summary']) === 0)
{
    $messages->summary = '<p class="text-error">Summary cannot be blank.</p>';
    return;
}

if (!isset($_POST['priority-group']) || strlen($_POST['priority-group']) === 0)
{
    $messages->priority = '<p class="text-error">A priority must be selected.</p>';
    return;
}

if (!isset($_POST['description']) || strlen($_POST['description']) === 0)
{
    $messages->description = '<p class="text-error">Description cannot be blank.</p>';
    return;
}

if (!isset($_POST['caller-id']) || strlen($_POST['caller-id']) === 0)
{
    $messages->callerID = '<p class="text-error">Caller ID cannot be blank</p>';
    return;
}

if (!isset($_POST['telephone-number']) || strlen($_POST['telephone-number']) === 0)
{
    $messages->telephoneNumber = '<p class="text-error">Telephone number cannot be blank.</p>';
    return;
}

if (!isset($_POST['problem-type']) || strlen($_POST['problem-type']) === 0)
{
    $messages->problemType = '<p class="text-error">Problem type cannot be blank.</p>';
    return;
}

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

$sqlHelpdeskOperator = getSecureText($_SESSION['EmployeeID'], $connection, true);
$sqlEntryDate = (new DateTime())->format('Y-m-d H:i:s');

$sqlResolutionID = 'null';

// Creates the resolution, then gets the resolution's ID
if (isset($_POST['resolution']))
{
    if (!isset($_POST['resolution-description']) || strlen($_POST['resolution-description']) === 0)
    {
        $messages->resolutionDescription = '<p class="text-error">Resolution description cannot be blank.</p>';
        return;
    }
    else
    {
        $sqlResolutionDescription = getSecureText($_POST['resolution-description'], $connection, true);

        $sql = "INSERT INTO Resolutions (CloseDate, Message, EmployeeID) VALUES ('{$sqlEntryDate}', '{$sqlResolutionDescription}', {$sqlHelpdeskOperator})";
        
        $result = $connection->query($sql);
        
        if (!$result)
        {
            $messages->resolutionDescription = '<p class="text-error">Error whilst adding resolution.</p>';
            return;
        }
        else
        {
            $sqlResolutionID = $connection->insert_id;
        }
    }
}

$sqlAssignedSpecialist = 'null';

// Check if the specialist is actually an IT specialist
if (isset($_POST['assign-specialist']))
{
    if (!isset($_POST['specialist-id']) || strlen($_POST['specialist-id']) === 0)
    {
        $messages->specialist = '<p class="text-error">Specialist\'s ID cannot be blank.</p>';
        return;
    }
    else
    {
        $sqlSpecialistID = getSecureText($_POST['specialist-id'], $connection, true);

        $sql = "SELECT EmployeeID FROM Employees WHERE EmployeeID={$sqlSpecialistID} AND JobTitle='IT Specialist'";
        
        $result = $connection->query($sql);
        
        if (!$result)
        {
            $messages->specialist = '<p class="text-error">Error whilst assigning specialist.</p>';
            return;
        }
        elseif ($result->num_rows !== 1)
        {
            $messages->specialist = '<p class="text-error">Specialist\'s ID is not valid.</p>';
            return;
        }
        else
        {
            $sqlAssignedSpecialist = $sqlSpecialistID;
        }
    }
}

// Stores data to be added to the database
$sqlSummary = getSecureText($_POST['summary'], $connection, true);
$sqlDescription = getSecureText($_POST['description'], $connection, true);
$sqlCallerID = getSecureText($_POST['caller-id'], $connection, true);
$sqlTelephoneNumber = getSecureText($_POST['telephone-number'], $connection, true);
$sqlProblemType = getSecureText($_POST['problem-type'], $connection, true);
$sqlOperatingSystem = getOS();
$sqlSoftware = getSoftware();
$sqlHardwareSerialID = getHardwareSerialID();
$sqlPriority = getSecureText($_POST['priority-group'], $connection, true);

$sqlSoftwareVersion = '';
if ($sqlOperatingSystem !== 'null' && isset($_POST['sofware-version'])) $sqlSoftwareVersion = getSecureText($_POST['sofware-version'], $connection, true);

$sqlOSVersion = '';
if ($sqlSoftware !== 'null' && isset($_POST['os-version'])) $sqlOSVersion = getSecureText($_POST['os-version'], $connection, true);

$fields = 'Summary, Description, EntryDate, CallerID, TelephoneNumber, HelpdeskOperator, AssignedSpecialist, ResolutionID, OperatingSystemID, SoftwareID, HardwareSerialID, ProblemType, Priority, SoftwareVersion, OperatingSystemVersion';

$values = "'{$sqlSummary}', '{$sqlDescription}', '{$sqlEntryDate}', {$sqlCallerID}, '{$sqlTelephoneNumber}', {$sqlHelpdeskOperator}, {$sqlAssignedSpecialist}, {$sqlResolutionID}, {$sqlOperatingSystem}, {$sqlSoftware}, {$sqlHardwareSerialID}, '{$sqlProblemType}', {$sqlPriority}, '{$sqlSoftwareVersion}', '{$sqlOSVersion}'";

$sql = "INSERT INTO Tickets ({$fields}) VALUES ({$values})";

$result = $connection->query($sql);

if (!$result)
{
    $messages->error = "<p class=\"text-error\">Unable to create ticket.</p><p>{$connection->error}</p><p>{$sql}</p>";
    return;
}

$ticketID = $connection->insert_id;

$connection->close();

header("Location: /ticket/?id={$ticketID}"); // Redirect to newly created ticket

/**
 * Gets the hardware serial id.
 * Or null if no text was inputted.
 */
function getHardwareSerialID()
{
    global $connection;
    
    if (isset($_POST['hardware-serial-id']) && strlen($_POST['hardware-serial-id']) > 0)
    {
        $id = getSecureText($_POST['hardware-serial-id'], $connection, true);
        
        return "'{$id}'";
    }
    
    return 'null';
}

/**
 * Gets the software.
 * Or null if no text was inputted.
 */
function getSoftware()
{
    global $connection;
    
    if (isset($_POST['software']) && strlen($_POST['software']) > 0)
    {
        $id = getSecureText($_POST['software'], $connection, true);
        
        return "'{$id}'";
    }
    
    return 'null';
}

/**
 * Gets the operating system.
 * Or null if no text was inputted.
 */
function getOS()
{
    global $connection;
    
    if (isset($_POST['operating-system']) && strlen($_POST['operating-system']) > 0)
    {
        $id = getSecureText($_POST['operating-system'], $connection, true);
        
        return "'{$id}'";
    }
    
    return 'null';
}

?>