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
