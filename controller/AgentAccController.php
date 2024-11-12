<?php
include '../entity/Agent.php';

$userAdmin = new Agent();

// Check if search filters are applied
$usernameFilter = isset($_GET['username']) ? $_GET['username'] : null;

// Fetch agent user accounts (only those with role_id = 5 for Used Car Agent)
$userAccounts = $userAdmin->getUserAccounts($usernameFilter, 5);  // Pass role_id = 5 to filter only Agents


// Fetch filtered user accounts (again, only those with role_id = 5)
$userAccounts = $userAdmin->getUserAccounts($usernameFilter, 5);
