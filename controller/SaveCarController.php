<?php
session_start();
include '../entity/Car.php';

class SaveCarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    public function saveCar($car_id, $user_id)
    {
        $stmt = $this->car->conn->prepare("SELECT COUNT(*) FROM saved_cars WHERE car_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $car_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            return false;
        }

        return $this->car->saveCar($car_id, $user_id);
    }

    public function getSavedCars($user_id)
    {
        return $this->car->getSavedCarsByUser($user_id);
    }

    public function getAllSavedCars()  // Changed method name
    {
        // Query to fetch all saved cars, without user-specific filter
        $query = "SELECT cars.*, saved_cars.user_id, users.username 
                  FROM saved_cars 
                  JOIN cars ON saved_cars.car_id = cars.car_id 
                  JOIN users ON saved_cars.user_id = users.id";

        $stmt = $this->car->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch all cars and return as an associative array
        return $result->fetch_all(MYSQLI_ASSOC);
    }



    public function unsaveCar($user_id, $car_id)
    {
        return $this->car->unsaveCar($car_id, $user_id);
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../boundary/loginPage.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $action = $_POST['action'] ?? 'save'; // Default to 'save' if 'action' is not set

    $controller = new SaveCarController();

    if ($action === 'unsave') {
        // Unsave car
        $result = $controller->unsaveCar($user_id, $car_id);
        $message = $result ? "Car unsaved successfully!" : "Failed to unsave car.";
    } else {
        // Save car
        $result = $controller->saveCar($car_id, $user_id);
        $message = $result ? "Car saved successfully!" : "Car already saved.";
    }

    header("Location: ../boundary/savedCars.php?message=" . urlencode($message));
    exit;
}
