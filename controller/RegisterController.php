<!-- Handles user registration logic -->
<?php
// Include User model only
include '../entity/UserAdmin.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role = $_POST['role'];

    // Initialize User model
    $user = new UserAdmin();

    // Validate user creation before proceeding
    if (validateCreateUser($user, $username, $email, $password, $role)) {
        // Create new user
        if ($user->createUser($username, $email, $password, $role)) {
            // Display popup and redirect on successful registration
            echo "<script>
                    alert('User registered successfully!');
                    window.location.href = '../boundary/registerPage.php';
                  </script>";
        } else {
            echo "Error: Unable to register user.";
        }
    }
}

// Function to validate user creation
function validateCreateUser($user, $username, $email, $password, $role) {
    // Check if email already exists
    if ($user->getUserByEmail($email)) {
        echo "<script>alert('Email already exists!');</script>";
        return false;
    }

    // Check if required fields are provided
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        echo "<script>alert('All fields are required!');</script>";
        return false;
    }

    return true;  // Return true if validation passes
}
?>
