<?php
include '../entity/Car.php';
session_start();

class SaveCarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    public function saveCar($car_id, $user_id)
    {
        // Check if the car is already saved by this user
        $stmt = $this->car->conn->prepare("SELECT COUNT(*) FROM saved_cars WHERE car_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $car_id, $user_id);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        // If the car is already saved, do not insert again
        if ($count > 0) {
            return false;
        }

        // Save car if not already saved
        return $this->car->saveCar($car_id, $user_id);
    }

    public function getSavedCars($user_id)
    {
        return $this->car->getSavedCarsByUser($user_id);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../boundary/loginPage.php");
        exit;
    }
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];

    $controller = new SaveCarController();
    $result = $controller->saveCar($car_id, $user_id);

    $message = $result ? "Car saved successfully!" : "Failed to save the car.";
    header("Location: ../boundary/buyerCarListings.php?message=" . urlencode($message));
    exit;
}
?>
