<?php

if (!isset($_GET['name'])) return;

require_once($_SERVER['DOCUMENT_ROOT'] . '/resources/page/utils/database.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/resources/page/utils/cleanup-utils.php');

if (!$connection) return;

$specialistQuery = getSpecialistQuery();

$searchTerm = getSecureText($_GET['name'], $connection, true);

$sql = "SELECT * FROM Helpdesk.Employees WHERE {$specialistQuery} CONCAT(FirstName, ' ', Surname) LIKE '{$searchTerm}%' LIMIT 3";

$result = $connection->query($sql);

if (!$result || $result->num_rows === 0) return;

while ($employee = $result->fetch_assoc())
{
    echo "
        <div class=\"content-section hpadding-small\">
            <h3>({$employee['EmployeeID']}) {$employee['FirstName']} {$employee['Surname']}</h3>
            <p>
                <strong>Department:</strong> {$employee['Department']}<br>
                <strong>Job Title:</strong> {$employee['JobTitle']}
            </p>
        </div>
    ";
}

/**
 * Gets the additional SQL query for when only searching for specialists.
 */
function getSpecialistQuery()
{
    if (isset($_GET['specialist']))
    {
        return "JobTitle='IT Specialist' AND";
    }
    
    return '';
}

?>