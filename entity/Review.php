<?php
include '../entity/db_connection.php';

class Review
{
    private $conn;

    // Constructor handles database connection internally
    public function __construct()
    {
        $this->conn = $this->connectDB();
    }

    // Function to connect to the database
    private function connectDB()
    {
        include '../entity/db_connection.php';
        return isset($conn) ? $conn : null;
    }

    public function addReview($user_id, $car_id, $review_text)
    {
        $stmt = $this->conn->prepare("INSERT INTO reviews (user_id, car_id, review_text) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $car_id, $review_text);
        return $stmt->execute();
    }
}
