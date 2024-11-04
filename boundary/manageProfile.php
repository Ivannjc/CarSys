<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create/Delete Profile</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<?php if (isset($_GET['message'])): ?>
    <script>
        <?php if ($_GET['message'] === 'created'): ?>
            alert("Role Created!");
        <?php elseif ($_GET['message'] === 'deleted'): ?>
            alert("Role Deleted!");
        <?php elseif ($_GET['message'] === 'create_error'): ?>
            alert("Error: Unable to add role.");
        <?php elseif ($_GET['message'] === 'delete_error'): ?>
            alert("Error: Unable to delete role.");
        <?php elseif ($_GET['message'] === 'updated'): ?>
            alert("Profile changes have been made!");
        <?php endif; ?>
    </script>
<?php endif; ?>

<body>
    <div class="navbar">
        <a href="useradminPage.php">Home</a>
        <a href="registerPage.php">Create User</a>
        <a href="manageProfile.php">Manage Profile</a>
        <a href="userAccounts.php">View Users</a>

        <form action="manageProfile.php" method="GET" class="searchform">
            <input type="text" name="search" placeholder="Search Profile" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <form action="../controller/LogoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <div class="content">
        <h2>Create or Remove a Profile</h2>
        <?php
        include '../controller/UserAccountController.php';
        $userAdmin = new UserAdmin();

        // Fetch roles from roles table with search filter
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        $roles = $userAdmin->getProfiles($searchTerm);
        ?>

        <div class="centered-form">
            <form action="../controller/ProfileController.php" method="POST">
                <input type="text" name="roleName" placeholder="Enter role name" required>
                <button type="submit" name="action" value="create">Create</button>
                <button type="submit" name="action" value="delete" class="delete-button">Delete</button>
            </form>
        </div>

        <!-- Table to Display and Edit Roles -->
        <h2>Edit User Profiles</h2>
        <form action="../controller/ProfileController.php" method="POST">
            <table>
                <tr>
                    <th>Profile Name</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($roles as $role): ?>
                    <?php if ($role !== 'Others'): ?>
                    <tr>
                        <td>
                            <input type="text" name="updatedRoles[<?php echo htmlspecialchars($role); ?>]" value="<?php echo htmlspecialchars($role); ?>" />
                        </td>
                        <td>
                            <button type="submit" name="update" value="<?php echo htmlspecialchars($role); ?>">Update</button>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            </table>
        </form>
    </div>
</body>
</html>
