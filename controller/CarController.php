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
}
