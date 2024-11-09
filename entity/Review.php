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

    public function getAllReviews()
    {
        $stmt = $this->conn->prepare("
        SELECT 
            users.username AS user_name,
            cars.make,
            cars.model,
            cars.year,
            cars.color,
            reviews.review_text
        FROM reviews
        JOIN users ON reviews.user_id = users.id
        JOIN cars ON reviews.car_id = cars.car_id
    ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
