<?php

$database = getenv('DB_DATABASE');
$host     = getenv('DB_HOST');
$username = getenv('DB_USERNAME');
$password = getenv('DB_PASSWORD');

$connection = new mysqli($host, $username, $password, $database);

?>