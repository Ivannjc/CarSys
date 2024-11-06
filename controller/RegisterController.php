<?php
// Include User model only
include '../entity/UserAdmin.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the password
    $role_id = $_POST['role']; // This is now the role_id

    // Initialize User model
    $user = new UserAdmin();

    // Validate user creation before proceeding
    if (validateCreateUser($user, $username, $email, $password, $role_id)) {
        // Create new user with the specified role and unique ID
        $isCreated = $user->createUser($username, $email, $password, $role_id);

        // Handle success/failure
        if ($isCreated) {
            echo "<script>
                alert('User registered successfully!');
                window.location.href = '../boundary/registerPage.php';
              </script>";
            exit();
        } else {
            echo "<script>
                    alert('User could not be created!');
                    window.location.href = '../boundary/registerPage.php';
                  </script>";
        }
    } else {
        echo "<script>
                    alert('Validation failed: Please check the input.');
                    window.location.href = '../boundary/registerPage.php';
                  </script>";
    }
}

// Function to validate user input before creating user
function validateCreateUser($user, $username, $email, $password, $role_id)
{
    // Validate if the email is already in use
    if ($user->getUserByEmail($email)) {
        echo "<script>
                    alert('Email already in use!');
                    window.location.href = '../boundary/registerPage.php';
                  </script>";
        return false;
    }

    // Additional validation checks
    if (strlen($password) < 6) {
        echo "<script>
                alert('Password must be at least 6 characters long!');
                window.location.href = '../boundary/registerPage.php';
              </script>";
        return false;
    }

    return true;
}
?>
