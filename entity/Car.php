<?php
class Car
{
    private $conn;

    public function __construct()
    {
        $this->conn = $this->connectDatabase();
    }

    private function connectDatabase()
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "carsystem";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    public function getAllCars($filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, transmission, description FROM cars WHERE 1=1";
        $params = [];
        $types = '';

        if (!empty($filters['make'])) {
            $query .= " AND make LIKE ?";
            $params[] = '%' . $filters['make'] . '%';
            $types .= 's';
        }
        if (!empty($filters['model'])) {
            $query .= " AND model LIKE ?";
            $params[] = '%' . $filters['model'] . '%';
            $types .= 's';
        }
        if (!empty($filters['year'])) {
            $query .= " AND year = ?";
            $params[] = $filters['year'];
            $types .= 'i';
        }

        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $cars = [];
        while ($car = $result->fetch_assoc()) {
            $cars[] = $car;
        }

        return $cars;
    }

    // Add Car
    public function addCar($make, $model, $year, $color, $price, $transmission, $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO cars (make, model, year, color, price, transmission, description) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Corrected the bind_param to match the types
        $stmt->bind_param("ssissss", $make, $model, $year, $color, $price, $transmission, $description);

        return $stmt->execute();
    }

    // Update Car
    public function updateCar($carId, $price, $description)
    {
        $stmt = $this->conn->prepare("UPDATE cars SET price = ?, description = ? WHERE car_id = ?");
        $stmt->bind_param("ssi", $price, $description, $carId);
        return $stmt->execute();
    }

    // Delete Car
    public function deleteCar($carId)
    {
        $stmt = $this->conn->prepare("DELETE FROM cars WHERE car_id = ?");
        $stmt->bind_param("i", $carId);
        return $stmt->execute();
    }
}
