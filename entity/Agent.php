<?php
class Agent
{
    private $mysqli;
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




    // Helper function to get role name by ID
    public function getRoleNameById($roleId)
    {
        $query = "SELECT role_name FROM roles WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $roleId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc()['role_name'] ?? null;
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

    public function getUserAccounts($usernameFilter = null, $roleFilter = null)
    {
        $query = "SELECT users.username, users.email, roles.role_name AS role, users.status 
                 FROM users 
                 LEFT JOIN roles ON users.role_id = roles.id
                 WHERE 1=1";
        $params = [];
        $types = '';

        // Filter by username if provided
        if ($usernameFilter) {
            $query .= " AND users.username LIKE ?";
            $params[] = '%' . $usernameFilter . '%';
            $types .= 's';
        }

        // Filter by role if provided (should be role_id, not role_name)
        if ($roleFilter) {
            $query .= " AND users.role_id = ?";
            $params[] = $roleFilter; // Use role_id (5 for Used Car Agents)
            $types .= 'i'; // Integer type for role_id
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

    public function addReview($user_id, $review, $rating)
    {
        $sql = "INSERT INTO agent_review (user_id, review, rating) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isi", $user_id, $review, $rating);

        return $stmt->execute();
    }

    public function getUserByUsername($username)
    {
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function getAgentReviews() {
        // Replace this with your actual database connection code
        include '../entity/db_connection.php';

        $query = "SELECT username, review_text, rating FROM agent_review";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
