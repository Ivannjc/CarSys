<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <title>Agent Accounts</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(message);
                window.location.href = 'agentAccounts.php'; // Clears the message from the URL
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
    <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <a href="viewReviews.php">View Car Reviews</a>
        <a href="agentAccounts.php">View Agents</a>
        <a href="sellerSavedCars.php">View Saved Cars</a>
        <a href="agentAccounts.php">View Agents</a> <!-- Change link to agentAccounts.php -->
        <form action="agentAccounts.php" method="GET" class="searchform">
            <input type="text" name="username" placeholder="Search by username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
            <button type="submit">Search</button>
        </form>

        <form action="../controller/LogoutController.php" method="POST" style="display:inline;" id="logoutButton" name="logoutButton">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Agent Accounts</h2>

    <?php
    include '../controller/AgentAccController.php';  // Include the new controller

    if (!empty($userAccounts)) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Review <br> Acount</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($userAccounts as $user) {
                    $customRole = htmlspecialchars($user['role']);
                    echo "<tr>";

                    // Editable username input
                    echo "<form action='../controller/AgentAccController.php' method='POST' class='useraccountform'>";
                    echo "<td>";
                    echo "<input type='text' name='updatedUsername' value='" . htmlspecialchars($user['username']) . "' required>";
                    echo "<input type='hidden' name='originalUsername' value='" . htmlspecialchars($user['username']) . "'>";
                    echo "</td>";

                    // Non-editable email display
                    echo "<td>";
                    echo htmlspecialchars($user['email']);
                    echo "</td>";

                    // Update role (only Agent role is allowed)
                    echo "<td>";
                    echo "<select id='role-select-" . htmlspecialchars($user['username']) . "' name='roles[" . htmlspecialchars($user['username']) . "]' onchange='toggleCustomRole(\"" . htmlspecialchars($user['username']) . "\")'>";
                    echo "<option value='Agent' selected>Used Car Agent</option>";
                    echo "</select>";
                    echo "</td>";

                    // Update button
                    echo "<td><button type='submit' name='review' value='" . htmlspecialchars($user['username']) . "'>Review</button></td>";
                    echo "</form>";

                 
                }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "<p style='color: white;'>No agent accounts found.</p>";
    }
    ?>
</body>

</html>
