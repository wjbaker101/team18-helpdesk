<?php

function checkSpecialist()
{
    if (isset($_POST['assign-specialist']))
    {
        if (!isset($_POST['specialist-id']) || strlen($_POST['specialist-id']) === 0)
        {
            $messages->specialist = '<p class="text-error">Specialist\'s ID cannot be blank.</p>';
        }
        else
        {
            $sqlSpecialistID = getSecureText($_POST['specialist-id'], $connection, true);

            $sql = "SELECT EmployeeID FROM Employees WHERE EmployeeID={$sqlSpecialistID}";

            $result = $connection->query($sql);

            if (!$result)
            {
                $messages->specialist = '<p class="text-error">Unable whilst adding resolution.</p>';
            }
            elseif ($result->num_rows !== 1)
            {
                $messages->specialist = '<p class="text-error">Specialist\'s ID is not valid.</p>';
            }
            else
            {
                return $sqlSpecialistID;
            }
        }
    }
    
    return 'null';
}

?>