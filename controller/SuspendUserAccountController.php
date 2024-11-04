<?php
include '../entity/UserAdmin.php';

if (isset($_POST['status']) && isset($_POST['username'])) {
    $username = $_POST['username'];
    $status = ($_POST['status'] === 'Inactive') ? 'Inactive' : 'Active';

    $userAdmin = new UserAdmin();
    $result = $userAdmin->setUserStatus($username, $status);

    if ($result) {
        $message = $status === "Inactive" ? 'User account set to Inactive successfully.' : 'User account set to Active successfully.';
        echo "<script>alert('$message'); window.location.href='../boundary/userAccounts.php';</script>";
    } else {
        echo "<script>alert('Failed to update user account status.'); window.location.href='../boundary/userAccounts.php';</script>";
    }
}
?>
