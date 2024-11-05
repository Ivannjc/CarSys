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
    $price = $_POST['price'];
    $transmission = $_POST['transmission'];
    $description = $_POST['description'];

    // Add car using the controller
    if ($controller->addCar($make, $model, $year, $color, $price, $transmission, $description)) {
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

    <script>
        // Function to show alert based on PHP message
        function showAlert(message) {
            if (message) {
                alert(message);
            }
        }

        // Call the showAlert function if a message exists
        window.onload = function() {
            <?php if (!empty($message)): ?>
                showAlert("<?php echo addslashes($message); ?>");
            <?php endif; ?>
        };
    </script>
    <style>
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            /* Set all fields to take full width of the form */
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
            /* Include padding and border in width calculation */
        }

        textarea {
            height: 100px;
            /* Set a specific height for the textarea */
        }

        button {
            width: 100%;
            /* Keep buttons the same width */
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .registerform {
            background-color: rgba(163, 162, 162, 0.9);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            /* Ensure items fill the available width */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Add a Car</h2>
    <form method="POST" action="addCarListings.php" class="registerform">
        <label for="make">Make:</label>
        <input type="text" id="make" name="make" required>

        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required>

        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required>

        <label for="color">Color:</label>
        <input type="text" id="color" name="color">

        <label for="price">Price:</label>
        <input type="number" id="price" name="price" step="0.01" required>

        <label for="transmission">Transmission:</label>
        <select id="transmission" name="transmission">
            <option value="Manual">Manual</option>
            <option value="Automatic">Automatic</option>
        </select>

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <br><br>
        <button type="submit">Add Car</button>
    </form>

</body>

</html>