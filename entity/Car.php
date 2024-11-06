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

    public function getAllCars($created_by, $filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, description FROM cars WHERE created_by = ?";

        $params = [$created_by];
        $types = 's';

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
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function addCar($make, $model, $year, $color, $price, $description, $created_by)
    {
        // Prepare the SQL query with placeholders
        $stmt = $this->conn->prepare("INSERT INTO cars (make, model, year, color, price, description, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param('ssisdss', $make, $model, $year, $color, $price, $description, $created_by);

        // Execute the query and return the result
        return $stmt->execute();
    }


    public function updateCar($car_id, $price, $description)
    {
        $stmt = $this->conn->prepare("UPDATE cars SET price = ?, description = ? WHERE car_id = ?");
        $stmt->bind_param('ssi', $price, $description, $car_id);
        return $stmt->execute();
    }

    public function deleteCar($car_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM cars WHERE car_id = ?");
        $stmt->bind_param('i', $car_id);
        return $stmt->execute();
    }

    // Method for fetching all cars (for buyers, without the created_by filter)
    public function getAllCarsForBuyer($filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, description FROM cars";

        $params = [];
        $types = '';

        if (!empty($filters['make'])) {
            $query .= " WHERE make LIKE ?";
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

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
