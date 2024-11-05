<?php
include '../entity/Car.php';

class CarController
{
    private $car;

    public function __construct()
    {
        $this->car = new Car(); // No need to pass the connection now
    }

    // Method to get car listings
    public function getCarListings($filters = [])
    {
        return $this->car->getAllCars($filters);
    }

    // Method to add a new car
    public function addCar($make, $model, $year, $color, $mileage, $price, $transmission, $fuel_type, $condition, $description)
    {
        return $this->car->addCar($make, $model, $year, $color, $mileage, $price, $transmission, $fuel_type, $condition, $description);
    }
}
