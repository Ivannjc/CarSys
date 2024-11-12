<?php
include '../entity/Car.php';

class AgentCarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    public function getCarListings($filters = [], $role_id)
    {
        // Only pass the filters and role_id, without created_by
        return $this->car->getAllCarsAgent($filters, $role_id);
    }

    public function addCar($make, $model, $year, $color, $price, $description, $created_by)
    {
        return $this->car->addCar($make, $model, $year, $color, $price, $description, $created_by);
    }

    public function updateCarListings($prices, $descriptions)
    {
        foreach ($prices as $carId => $price) {
            $description = isset($descriptions[$carId]) ? $descriptions[$carId] : '';
            $this->car->updateCar($carId, $price, $description);
        }
    }

    public function deleteCar($car_id)
    {
        return $this->car->deleteCar($car_id);
    }

    public function incrementViewCount($car_id)
    {
        return $this->car->incrementViewCount($car_id);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $carController = new AgentCarController();
    $created_by = $_SESSION['email'];  // Still needed for addCar method, not for search

    if (isset($_POST['add'])) {
        $make = $_POST['make'];
        $model = $_POST['model'];
        $year = $_POST['year'];
        $color = $_POST['color'];
        $price = $_POST['price'];
        $description = $_POST['description'];

        if ($carController->addCar($make, $model, $year, $color, $price, $description, $created_by)) {
            header("Location: ../boundary/agentCarListings.php?message=Car added successfully");
        } else {
            header("Location: ../boundary/agentAddCarListings.php?message=Failed to add car");
        }
        exit;
    }

    if (isset($_POST['update'])) {
        $prices = $_POST['price'];
        $descriptions = $_POST['description'];
        $carController->updateCarListings($prices, $descriptions);
        header("Location: ../boundary/agentCarListings.php?message=Car listings updated successfully");
        exit;
    }

    if (isset($_POST['delete'])) {
        $carId = $_POST['delete'];
        $carController->deleteCar($carId);
        header("Location: ../boundary/agentCarListings.php?message=Car listing deleted successfully");
        exit;
    }

    if (isset($_POST['view_description'])) {
        $car_id = $_POST['car_id'];
        $carModel = new Car();
        $carModel->incrementViewCount($car_id);
        $description = $carModel->getCarDescription($car_id);
        echo json_encode(['description' => $description]);
        exit;
    }
}
