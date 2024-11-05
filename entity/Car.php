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

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        return $conn;
    }

    // Fetch all cars
    public function getAllCars($filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, transmission, description FROM cars WHERE 1=1";
        $params = [];
        $types = ''; // To keep track of the types of parameters

        if (!empty($filters['make'])) {
            $query .= " AND make LIKE ?";
            $params[] = '%' . $filters['make'] . '%';
            $types .= 's'; // String type
        }
        if (!empty($filters['model'])) {
            $query .= " AND model LIKE ?";
            $params[] = '%' . $filters['model'] . '%';
            $types .= 's'; // String type
        }
        if (!empty($filters['year'])) {
            $query .= " AND year = ?";
            $params[] = $filters['year'];
            $types .= 'i'; // Integer type
        }

        $stmt = $this->conn->prepare($query);
        // Bind parameters dynamically
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result(); // Get the result set from the statement

        // Fetch all results into an array
        $cars = [];
        while ($car = $result->fetch_assoc()) {
            $cars[] = $car;
        }

        return $cars;
    }

    // Add car
    public function addCar($make, $model, $year, $color, $mileage, $price, $transmission, $fuel_type, $condition, $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO cars (make, model, year, color, mileage, price, transmission, fuel_type, condition, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssisdsdsss", $make, $model, $year, $color, $mileage, $price, $transmission, $fuel_type, $condition, $description);
        return $stmt->execute();
    }
}
