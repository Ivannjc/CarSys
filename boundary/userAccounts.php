<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <title>User Accounts</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(message);
                window.location.href = 'userAccounts.php'; // Clears the message from the URL
            }
        };
        
        function toggleCustomRole(username) {
            var selectElement = document.getElementById('role-select-' + username);
            var customRoleInput = document.getElementById('custom-role-input-' + username);

            if (selectElement.value === 'other') {
                customRoleInput.style.display = 'inline-block';
                customRoleInput.required = true;
            } else {
                customRoleInput.style.display = 'none';
                customRoleInput.required = false;
            }
        }
    </script>
</head>

<body>
    <div class="navbar">
        <a href="useradminPage.php">Home</a>
        <a href="registerPage.php">Create User</a>
        <a href="manageProfile.php">Manage Profile</a>
        <a href="userAccounts.php">View Users</a>
        <form action="userAccounts.php" method="GET" class="searchform">
            <input type="text" name="username" placeholder="Search by username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
            <select name="role">
                <option value="">All Profiles</option>
                <option value="Seller" <?php echo (isset($_GET['role']) && $_GET['role'] === 'Seller') ? 'selected' : ''; ?>>Seller</option>
                <option value="Buyer" <?php echo (isset($_GET['role']) && $_GET['role'] === 'Buyer') ? 'selected' : ''; ?>>Buyer</option>
                <option value="Agent" <?php echo (isset($_GET['role']) && $_GET['role'] === 'Agent') ? 'selected' : ''; ?>>Used Car Agent</option>
            </select>
            <button type="submit">Search</button>
        </form>

        <form action="../controller/LogoutController.php" method="POST" style="display:inline;" id="logoutButton" name="logoutButton">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>User Accounts</h2>

    <?php
    include '../controller/UserAccountController.php';

    if (!empty($userAccounts)) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Update <br> Profile</th>
                    <th>Suspend <br> Account</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($userAccounts as $user) {
                    $customRole = htmlspecialchars($user['role']);
                    if ($customRole != 'Useradmin') {
                        echo "<tr>";

                        // Editable username input
                        echo "<form action='../controller/UserAccountController.php' method='POST' class='useraccountform'>";
                        echo "<td>";
                        echo "<input type='text' name='updatedUsername' value='" . htmlspecialchars($user['username']) . "' required>";
                        echo "<input type='hidden' name='originalUsername' value='" . htmlspecialchars($user['username']) . "'>";
                        echo "</td>";

                        // Non-editable email display
                        echo "<td>";
                        echo htmlspecialchars($user['email']);
                        echo "</td>";

                        echo "<td>";
                        echo "<select id='role-select-" . htmlspecialchars($user['username']) . "' name='roles[" . htmlspecialchars($user['username']) . "]' onchange='toggleCustomRole(\"" . htmlspecialchars($user['username']) . "\")'>";

                        // Dynamically generated roles from the database with role_id as the value
                        foreach ($roles as $role) {
                            $selected = ($user['role'] == $role['role_name']) ? 'selected' : '';
                            echo "<option value='" . htmlspecialchars($role['id']) . "' $selected>" . htmlspecialchars($role['role_name']) . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";

                        // Update button
                        echo "<td><button type='submit' name='update' value='" . htmlspecialchars($user['username']) . "'>Update</button></td>";
                        echo "</form>";

                        // Suspend account form
                        echo "<td>
                                <form action='../controller/SuspendUserAccountController.php' method='POST'>
                                    <select name='status' onchange='this.form.submit()'>
                                        <option value='Active' " . (isset($user['status']) && $user['status'] === 'Active' ? 'selected' : '') . ">Active</option>
                                        <option value='Inactive' " . (isset($user['status']) && $user['status'] === 'Inactive' ? 'selected' : '') . ">Inactive</option>
                                    </select>
                                    <input type='hidden' name='username' value='" . htmlspecialchars($user['username']) . "'>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "<p style='color: white;'>No user accounts found.</p>";
    }
    ?>
</body>

</html>