<?php
include_once '../controller/CarController.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new CarController();
    
    // Get form data
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $mileage = $_POST['mileage'];
    $price = $_POST['price'];
    $transmission = $_POST['transmission'];
    $fuel_type = $_POST['fuel_type'];
    $condition = $_POST['condition'];
    $description = $_POST['description'];

    // Add car using the controller
    if ($controller->addCar($make, $model, $year, $color, $mileage, $price, $transmission, $fuel_type, $condition, $description)) {
        $message = "Car added successfully!";
    } else {
        $message = "Failed to add car.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Cars</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <h2>Add a Car</h2>
    <?php if (!empty($message)): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form method="POST" action="manageCarListings.php">
        <label for="make">Make:</label>
        <input type="text" id="make" name="make" required>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color">

        <label for="mileage">Mileage:</label>
        <input type="number" id="mileage" name="mileage">

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="transmission">Transmission:</label>
        <select id="transmission" name="transmission">
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
        </select>

        <label for="fuel_type">Fuel Type:</label>
        <select id="fuel_type" name="fuel_type">
            <option value="Petrol">Petrol</option>
            <option value="Diesel">Diesel</option>
            <option value="Electric">Electric</option>
            <option value="Hybrid">Hybrid</option>
        </select>

        <label for="condition">Condition:</label>
        <select id="condition" name="condition">
            <option value="New">New</option>
            <option value="Used">Used</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <button type="submit">Add Car</button>
    </form>
</body>
</html>
