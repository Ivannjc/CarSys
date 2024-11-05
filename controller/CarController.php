<?php
include '../entity/Car.php';

class CarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    public function getCarListings($filters = [])
    {
        return $this->car->getAllCars($filters);
    }

    public function addCar($make, $model, $year, $color, $price, $transmission, $description)
    {
        return $this->car->addCar($make, $model, $year, $color, $price, $transmission, $description);
    }

    public function updateCarListings($prices, $descriptions)
    {
        foreach ($prices as $carId => $price) {
            $description = isset($descriptions[$carId]) ? $descriptions[$carId] : '';
            $this->car->updateCar($carId, $price, $description);
        }
    }

    public function deleteCar($carId)
    {
        return $this->car->deleteCar($carId);
    }
}

// Handle POST requests from carListings.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carController = new CarController(); // Instantiate the controller here

    if (isset($_POST['update'])) {
        $prices = $_POST['price'];
        $descriptions = $_POST['description'];
        $carController->updateCarListings($prices, $descriptions);

        // Redirect back to the car listings with a success message
        header("Location: ../boundary/carListings.php?message=Car listings updated successfully");
        exit;
    }

    // Check if a delete request has been made
    if (isset($_POST['delete']) && isset($_POST['car_id'])) {
        $carId = $_POST['car_id'];
        $carController->deleteCar($carId); // Now this will work

        // Redirect back to the car listings with a success message
        header("Location: ../boundary/carListings.php?message=Car listing deleted successfully");
        exit;
    }
}
