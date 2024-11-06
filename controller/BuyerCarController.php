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
    public function getAllCars()
    {
        return $this->car->getAllCarsForBuyer(); // Now using the new method for buyers
    }

    public function getSavedCars($user_id)
    {
        return $this->car->getSavedCarsByUser($user_id);
    }
}

// Handle GET requests to display cars to the buyer
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    session_start();

    // Instantiate the controller
    $buyerCarController = new BuyerCarController();

    // Fetch all cars for the buyer
    $cars = $buyerCarController->getAllCars();
}
