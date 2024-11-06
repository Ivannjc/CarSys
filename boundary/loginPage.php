<?php
include '../entity/db_connection.php'; // Adjust the path to db_connection.php

function displayLoginPage($roles)
{
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
        <style>
            .loginform {
                background-color: rgba(255, 255, 255, 0.9);
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
                width: 40%;
                margin: 100px auto;
                display: flex;
                flex-direction: column;
                text-align: left;
                font-family: 'Arial', sans-serif;
                color: #333;
            }

            .loginform label {
                margin-bottom: 10px;
                font-size: 16px;
                font-weight: bold;
            }

            .loginform input[type="text"],
            .loginform input[type="password"],
            .loginform select {
                padding: 10px;
                font-size: 14px;
                border: 1px solid #ccc;
                border-radius: 5px;
                margin-bottom: 15px;
                width: 100%;
                box-sizing: border-box;
            }

            .loginform button {
                padding: 12px;
                font-size: 16px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
            }

            .loginform button:hover {
                background-color: #45a049;
            }

            h2 {
                text-align: center;
                color: #ccc;
                margin-top: 50px;
            }
        </style>
    </head>

    <body>
        <h2>Login to an account</h2>

        <form action="../controller/LoginController.php" method="POST" class="loginform">
            <label for="username">Email:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="role">Role:</label>
            <select id="role" name="role">
                <?php foreach ($roles as $role): ?>
                    <option value="<?php echo htmlspecialchars($role['role_name']); ?>">
                        <?php echo htmlspecialchars($role['role_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <br><br>
            <button type="submit">Login</button>
        </form>
    </body>

    </html>
<?php
}

// Fetch roles from the database
$roles = [];
$sql = "SELECT role_name FROM roles";
$result = $conn->query($sql);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $roles[] = $row;
    }
} else {
    echo "Error fetching roles: " . $conn->error;
}

// Close the database connection
$conn->close();

// Call the function to display the login page with roles
displayLoginPage($roles);
?>
