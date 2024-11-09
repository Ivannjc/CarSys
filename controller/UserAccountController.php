<?php
include '../entity/UserAdmin.php';

$userAdmin = new UserAdmin();
// Retrieve all roles
$roles = $userAdmin->getRoles(); // This method should return both 'role_name' and 'id'

// Check if search filters are applied
$usernameFilter = isset($_GET['username']) ? $_GET['username'] : null;
$roleFilter = isset($_GET['role']) ? $_GET['role'] : null;

// Fetch user accounts
$userAccounts = $userAdmin->getUserAccounts($usernameFilter, $roleFilter);

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $roles = $_POST['roles'];
    $username = $_POST['update'];
    $originalUsername = $_POST['originalUsername'];
    $updatedUsername = $_POST['updatedUsername'];

    // Get the selected role ID for the user
    $newRoleId = $roles[$username];

    // Update user role, username, and email using the role ID
    if ($userAdmin->updateUserAccountDetails($originalUsername, $updatedUsername, $newRoleId)) {
        header('Location: ../boundary/userAccounts.php?message=Profile updated successfully!');
        exit();
    } else {
        echo "Error: Unable to update user role, username, or email.";
    }
    
}


// Fetch filtered user accounts
$userAccounts = $userAdmin->getUserAccounts($usernameFilter, $roleFilter);
