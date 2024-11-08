<?php
class Car
{
    public $conn;

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

    // Get all cars created by a specific user
    public function getAllCars($created_by, $filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, description, view_count FROM cars WHERE created_by = ?";

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

    // Add a new car to the database
    public function addCar($make, $model, $year, $color, $price, $description, $created_by)
    {
        // Prepare the SQL query with placeholders
        $stmt = $this->conn->prepare("INSERT INTO cars (make, model, year, color, price, description, created_by, view_count) VALUES (?, ?, ?, ?, ?, ?, ?, 0)");

        $stmt->bind_param('ssisdss', $make, $model, $year, $color, $price, $description, $created_by);

        // Execute the query and return the result
        return $stmt->execute();
    }

    // Update car details
    public function updateCar($car_id, $price, $description)
    {
        $stmt = $this->conn->prepare("UPDATE cars SET price = ?, description = ? WHERE car_id = ?");
        $stmt->bind_param('ssi', $price, $description, $car_id);
        return $stmt->execute();
    }

    // Delete a car from the database
    public function deleteCar($car_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM cars WHERE car_id = ?");
        $stmt->bind_param('i', $car_id);
        return $stmt->execute();
    }

    // Fetch all cars for buyers with optional filters
    public function getAllCarsForBuyer($filters = [])
    {
        $query = "SELECT car_id, make, model, year, color, price, description, view_count FROM cars";
        $params = [];
        $types = '';
        $whereClauses = [];

        if (!empty($filters['make'])) {
            $whereClauses[] = "make LIKE ?";
            $params[] = '%' . $filters['make'] . '%';
            $types .= 's';
        }
        if (!empty($filters['model'])) {
            $whereClauses[] = "model LIKE ?";
            $params[] = '%' . $filters['model'] . '%';
            $types .= 's';
        }
        if (!empty($filters['year'])) {
            $whereClauses[] = "year = ?";
            $params[] = $filters['year'];
            $types .= 'i';
        }

        if ($whereClauses) {
            $query .= " WHERE " . implode(" AND ", $whereClauses);
        }

        $stmt = $this->conn->prepare($query);
        if ($params) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Save a car to a user's saved cars list
    public function saveCar($car_id, $user_id)
    {
        $stmt = $this->conn->prepare("INSERT INTO saved_cars (car_id, user_id) VALUES (?, ?)");
        $stmt->bind_param('ii', $car_id, $user_id);
        return $stmt->execute();
    }

    // Remove a car from a user's saved cars list
    public function unsaveCar($car_id, $user_id)
    {
        $stmt = $this->conn->prepare("DELETE FROM saved_cars WHERE car_id = ? AND user_id = ?");
        $stmt->bind_param('ii', $car_id, $user_id);
        return $stmt->execute();
    }

    // Get all saved cars for a user
    public function getSavedCarsByUser($user_id)
    {
        $stmt = $this->conn->prepare(
            "SELECT cars.car_id, cars.make, cars.model, cars.year, cars.color, cars.price, cars.description 
         FROM saved_cars 
         JOIN cars ON saved_cars.car_id = cars.car_id 
         WHERE saved_cars.user_id = ?"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Increment the view count of a specific car
    public function incrementViewCount($car_id)
    {
        $stmt = $this->conn->prepare("UPDATE cars SET view_count = view_count + 1 WHERE car_id = ?");
        $stmt->bind_param('i', $car_id);
        return $stmt->execute();
    }

    // Fetch the description of a specific car
    public function getCarDescription($car_id)
    {
        $stmt = $this->conn->prepare("SELECT description FROM cars WHERE car_id = ?");
        $stmt->bind_param('i', $car_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row['description'] ?? '';
    }
}
?>
