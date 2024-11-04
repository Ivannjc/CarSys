<?php
class UserAdmin
{
    private $conn;

    // Constructor to handle database connection internally
    public function __construct()
    {
        $this->conn = $this->connectDB();
    }

    // Function to connect to the database
    private function connectDB()
    {
        include '../entity/db_connection.php'; // Include database connection
        if (!$conn) {
            die("Database connection failed: " . mysqli_connect_error());
        }
        return $conn;
    }

    public function getUserByUsernameOrEmail($identifier) {
        // Assuming you have a PDO connection set up as $this->conn
        $query = "SELECT * FROM users WHERE username = :identifier OR email = :identifier LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':identifier', $identifier);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Return user data if found
    }
    

    // Function to create a user
    public function createUser($username, $email, $password, $role_id)
    {
        $query = "INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sssi", $username, $email, $password, $role_id); // role_id as an integer
        return $stmt->execute();
    }

    // Function to get a user by email
    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Fetch user accounts with role from the roles table
    public function getUserAccounts($usernameFilter = null, $roleFilter = null)
    {
        $query = "SELECT users.username, users.email, roles.role_name AS role, users.status 
                  FROM users 
                  LEFT JOIN roles ON users.role_id = roles.id
                  WHERE 1=1";
        $params = [];
        $types = '';

        if ($usernameFilter) {
            $query .= " AND users.username LIKE ?";
            $params[] = '%' . $usernameFilter . '%';
            $types .= 's';
        }

        if ($roleFilter) {
            $query .= " AND roles.role_name = ?";
            $params[] = $roleFilter;
            $types .= 's';
        }

        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $userAccounts = [];

        while ($row = $result->fetch_assoc()) {
            $userAccounts[] = $row;
        }

        return $userAccounts;
    }

    // User status, active or inactive
    public function setUserStatus($username, $status)
    {
        $query = "UPDATE users SET status = ? WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $status, $username); // Both status and username as strings
        return $stmt->execute();
    }

    // Create role in the roles table
    public function addRole($roleName)
    {
        $query = "INSERT INTO roles (role_name) VALUES (?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $roleName);
        return $stmt->execute();
    }

    // Retrieve all roles from the roles table for "View User"
    public function getRoles()
    {
        $query = "SELECT id, role_name FROM roles";
        $result = $this->conn->query($query);

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = ['id' => $row['id'], 'role_name' => $row['role_name']];
        }
        return $roles;
    }

    // Retrieve all roles from roles table with optional search term
    public function getProfiles($searchTerm = '')
    {
        $query = "SELECT role_name FROM roles";

        if ($searchTerm) {
            $query .= " WHERE role_name LIKE ?";
            $stmt = $this->conn->prepare($query);
            $likeSearch = '%' . $searchTerm . '%';
            $stmt->bind_param("s", $likeSearch);
        } else {
            $stmt = $this->conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row['role_name'];
        }
        return $roles;
    }




    // Delete role from the roles table and reassign users to "Others"
    public function deleteRole($roleName)
    {
        // Get the role ID by role name
        $roleId = $this->getRoleIdByName($roleName);

        // Ensure the role ID exists
        if ($roleId) {
            // Reassign all users with this role ID to "Others" (role_id = 2)
            $reassignQuery = "UPDATE users SET role_id = 2 WHERE role_id = ?";
            $stmtReassign = $this->conn->prepare($reassignQuery);
            $stmtReassign->bind_param("i", $roleId);
            $stmtReassign->execute();

            // Now proceed to delete the role
            $query = "DELETE FROM roles WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $roleId);
            return $stmt->execute();
        }
        return false;
    }


    // Fetch the role ID by role name
    public function getRoleIdByName($roleName)
    {
        $query = "SELECT id FROM roles WHERE role_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $roleName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['id'] ?? null; // Return null if no role found
    }


    // Update role name in the roles table and in user records
    public function updateRoleAndUsers($originalRole, $updatedRole)
    {
        // Update role in roles table
        $query = "UPDATE roles SET role_name = ? WHERE role_name = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $updatedRole, $originalRole);

        if ($stmt->execute()) {
            // Update all user records with the original role
            // Get the original role's ID
            $roleIdQuery = "SELECT id FROM roles WHERE role_name = ?";
            $roleIdStmt = $this->conn->prepare($roleIdQuery);
            $roleIdStmt->bind_param("s", $originalRole);
            $roleIdStmt->execute();
            $result = $roleIdStmt->get_result();
            $originalRoleId = $result->fetch_assoc()['id'];

            // Update the users to the new role ID
            $newRoleIdQuery = "SELECT id FROM roles WHERE role_name = ?";
            $newRoleIdStmt = $this->conn->prepare($newRoleIdQuery);
            $newRoleIdStmt->bind_param("s", $updatedRole);
            $newRoleIdStmt->execute();
            $newRoleId = $newRoleIdStmt->get_result()->fetch_assoc()['id'];

            // Update user records with the original role
            $userQuery = "UPDATE users SET role_id = ? WHERE role_id = ?";
            $userStmt = $this->conn->prepare($userQuery);
            $userStmt->bind_param("ii", $newRoleId, $originalRoleId);
            return $userStmt->execute();
        }
        return false;
    }

    public function updateUserAccountDetails($username, $updatedUsername, $updatedEmail, $updatedRoleId)
    {
        $query = "UPDATE users SET username = ?, email = ?, role_id = ? WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssis", $updatedUsername, $updatedEmail, $updatedRoleId, $username);
        return $stmt->execute();
    }

    // Function to fetch all roles from the roles table
    // For register page
    public function getAllRoles()
    {
        $query = "SELECT id, role_name FROM roles"; // Fetch both id and role_name
        $result = $this->conn->query($query);

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = ['id' => $row['id'], 'role_name' => $row['role_name']];
        }
        return $roles; // Return roles as an array of associative arrays
    }
}
