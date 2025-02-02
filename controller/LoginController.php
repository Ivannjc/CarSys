<?php
session_start();
include '../entity/User.php'; // Ensure this connects to the database correctly

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $selectedRole = $_POST['role'];

    // SQL query to fetch user details based on username or email and role
    $sql = "SELECT id, username, email, password, role_id, status FROM users WHERE (username = ? OR email = ?) AND status = 'Active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username); // Bind username for both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password with password_verify
        if (password_verify($password, $user['password'])) {
            // Check if the selected role matches the user’s role
            if (($selectedRole === 'User Admin' && $user['role_id'] == 1) ||
                ($selectedRole === 'Seller' && $user['role_id'] == 3) ||
                ($selectedRole === 'Buyer' && $user['role_id'] == 4) ||
                ($selectedRole === 'Used Car Agent' && $user['role_id'] == 5)
            ) {

                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['email'] = $user['email']; // Set the email in the session

                // Redirect to the appropriate page based on role_id
                switch ($user['role_id']) {
                    case 1: // User Admin
                        header("Location: ../boundary/useradminPage.php");
                        break;
                    case 3:  // Seller 
                        header("Location: ../boundary/carListings.php");
                        break;
                    case 4:  // Buyer
                        header("Location: ../boundary/buyerCarListings.php");
                        break;
                    case 5:  // Used Car Agent
                        header("Location: ../boundary/agentCarListings.php");
                        break;
                    default:
                        // Default redirect for User Admin or any other roles
                        header("Location: ../boundary/useradminPage.php");
                        break;
                }
                exit();
            } else {
                echo "<script>alert('Role mismatch. Please select the correct role.'); window.location.href='../boundary/loginPage.php';</script>";
            }
        } else {
            echo "<script>alert('Incorrect password. Please try again.'); window.location.href='../boundary/loginPage.php';</script>";
        }
    } else {
        echo "<script>alert('User not found or inactive. Please try again.'); window.location.href='../boundary/loginPage.php';</script>";
    }
    $stmt->close();
}
$conn->close();
?>
