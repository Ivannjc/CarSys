<?php
include '../entity/Car.php'; // Ensure Car class is included to interact with the database

class BuyerCarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car();
    }

    // Fetch all cars for buyers (without the created_by filter)
    public function getAllCars($filters = [])
    {
        return $this->car->getAllCars($filters); // Pass filters to the Car model
    }

    public function getSavedCars($user_id)
    {
        return $this->car->getSavedCarsByUser($user_id);
    }
}

// Handle GET requests to display cars to the buyer
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Instantiate the controller
    $buyerCarController = new BuyerCarController();

    // Fetch all cars for the buyer
    $cars = $buyerCarController->getAllCars();
}
