<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Admin Page</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://digitalsynopsis.com/wp-content/uploads/2014/06/supercar-wallpapers-bugatti-1.jpg');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            margin: 0;
        }

        /* Center the heading and add spacing */
        h2 {
            text-align: center;
            color: #eeeeee;
            margin-bottom: 20px;
            /* Adjusts space between the heading and form */
        }

        /* Form styling */
        form {
            background-color: rgba(163, 162, 162, 0.9);
            /* White with slight transparency */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            /* Adjusted width for better presentation */
            display: flex;
            flex-direction: column;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 10px;
            /* Add spacing between buttons */
        }

        button:hover {
            background-color: #45a049;
        }

        .logoutButton {
            background-color: rgb(243, 78, 78);
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <h2>User Admin Dashboard</h2>

    <form>
        <a href="userAccounts.php">
            <button type="button">View Users</button>
        </a>
        </a>
        <a href="registerPage.php">
            <button type="button">Create Account</button>
        </a>
        <a href="manageProfile.php">
            <button type="button">Manage Profile</button>
        </a>
    </form>
    <form action="../controller/LogoutController.php" method="POST" style="display:inline;" id="logoutButton" name="logoutButton">
        <button class="logout-button" type="submit">Logout</button>
    </form>
</body>

</html>