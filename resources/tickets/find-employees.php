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
    $specialistInformation = '';
    
    if (isset($_GET['specialist']) && $_GET['specialist'] === 'true')
    {
        $sql = "SELECT Specialisation FROM Specialisations WHERE EmployeeID={$employee['EmployeeID']}";
        
        $result2 = $connection->query($sql);
        
        $specialisations = array();
        
        if ($result2 && $result2->num_rows > 0)
        {
            while ($specialisation = $result2->fetch_assoc())
            {
                $specialisations[] = $specialisation['Specialisation'];
            }
        
            $specialisationsFormatted = join(', ', $specialisations);

            $specialistInformation = "
                <p>
                    <strong>Specialisations:</strong><br>
                    {$specialisationsFormatted}
                </p>
            ";
        }
    }
    
    echo "
        <div class=\"content-section hpadding-small\">
            <h3>({$employee['EmployeeID']}) {$employee['FirstName']} {$employee['Surname']}</h3>
            <p>
                <strong>Department:</strong> {$employee['Department']}<br>
                <strong>Job Title:</strong> {$employee['JobTitle']}
            </p>
            <p>{$specialistInformation}</p>
        </div>
    ";
}

/**
 * Gets the additional SQL query for when only searching for specialists.
 */
function getSpecialistQuery()
{
    if (isset($_GET['specialist']) && $_GET['specialist'] === 'true')
    {
        return "JobTitle='IT Specialist' AND";
    }
    
    return '';
}

?>