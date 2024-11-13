<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register New User</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
    <span class="welcome-message">Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?>!</span>
        <a href="useradminPage.php">Home</a>
        <a href="registerPage.php">Create User</a>
        <a href="manageProfile.php">Manage Profile</a>
        <a href="userAccounts.php">View Users</a>
        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>
    <!-- Heading for the page -->
    <h2>Register New User</h2>

    <!-- Form to register a new user -->
    <form action="../controller/RegisterController.php" method="POST" class="registerform">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="role">Role:</label>
        <select id="role" name="role">
            <?php
            include '../controller/UserAccountController.php'; // Include your UserAdmin controller
            $userAdmin = new UserAdmin(); // Create an instance of UserAdmin
            $roles = $userAdmin->getAllRoles(); // Fetch all roles from the database

            // Generate dropdown options based on fetched roles
            foreach ($roles as $role) {
                echo '<option value="' . htmlspecialchars($role['id']) . '">' . htmlspecialchars($role['role_name']) . '</option>'; // Use role id for value and role_name for display
            }
            ?>
        </select>



        <br><br>
        <button type="submit">Register</button>
    </form>

</body>

</html>