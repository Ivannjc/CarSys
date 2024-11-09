<?php
include '../controller/CarController.php';

$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $controller = new CarController();

    // Get form data (removed transmission)
    $make = $_POST['make'];
    $model = $_POST['model'];
    $year = $_POST['year'];
    $color = $_POST['color'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    // Ensure 'created_by' is passed from session
    $created_by = $_SESSION['email']; // Assuming the email is stored in session

    // Add car using the controller
    if ($controller->addCar($make, $model, $year, $color, $price, $description, $created_by)) {
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
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            height: 100px;
        }

        button {
            width: 100%;
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
        }
    </style>
</head>

<body>
    <div class="navbar">
        <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <a href="viewReviews.php">View Car Reviews</a>
        <a href="sellerSavedCars.php">View Saved Cars</a>

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

        <label for="description">Description:</label>
        <textarea id="description" name="description"></textarea>

        <br><br>
        <button type="submit">Add Car</button>
    </form>

</body>

</html>