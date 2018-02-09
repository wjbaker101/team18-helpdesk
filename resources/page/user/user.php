<?php

require_once(ROOT . '/resources/page/utils/database.php');
require_once(ROOT . '/resources/page/utils/cleanup-utils.php');

$employee = null;

if (!$connection) return;

if (!isset($_SESSION['EmployeeID']) || !isset($_SESSION['Password'])) return;

$employeeId = $_SESSION['EmployeeID'];
$hashedPassword = $_SESSION['Password'];

$sqlEmployeeId = getSecureText($employeeId, $connection, true);
$sqlPassword = getSecureText($hashedPassword, $connection, true);

$sql = "SELECT * FROM Employees, EmployeeLogins WHERE Employees.EmployeeID={$sqlEmployeeId} AND EmployeeLogins.Password='{$sqlPassword}'";

$result = $connection->query($sql);

if (!$result || $result->num_rows !== 1) return;

$employee = $result->fetch_assoc();

?>