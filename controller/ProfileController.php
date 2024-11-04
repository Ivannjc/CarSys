<?php
include '../entity/UserAdmin.php';

// Instantiate UserAdmin object
$userAdmin = new UserAdmin();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        $roleName = trim($_POST['roleName']);

        // Create role
        if ($action === 'create') {
            if ($userAdmin->addRole($roleName)) {
                header('Location: ../boundary/manageProfile.php?message=created');
                exit();
            } else {
                header('Location: ../boundary/manageProfile.php?message=create_error');
                exit();
            }
        }
        // Delete role
        elseif ($action === 'delete') {
            if ($userAdmin->deleteRole($roleName)) {
                header('Location: ../boundary/manageProfile.php?message=deleted');
                exit();
            } else {
                header('Location: ../boundary/manageProfile.php?message=delete_error');
                exit();
            }
        }
    }

    // Update role
    if (isset($_POST['update'])) {
        $originalRole = $_POST['update'];
        $updatedRole = trim($_POST['updatedRoles'][$originalRole]);

        if ($userAdmin->updateRoleAndUsers($originalRole, $updatedRole)) {
            header('Location: ../boundary/manageProfile.php?message=updated');
            exit();
        } else {
            header('Location: ../boundary/manageProfile.php?message=update_error');
            exit();
        }
    }
}
