<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Car Listings</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(message);
                window.location.href = 'carListings.php'; // Clears the message from the URL
            }
        };
    </script>
</head>

<body>
    <!-- Navbar with link to manageCarListings and search form -->
    <div class="navbar">
        <a href="addCarListings.php">Add Car Listings</a>
        <form action="carListings.php" method="GET" class="searchform">
            <input type="text" name="make" placeholder="Search by Make" value="<?php echo isset($_GET['make']) ? htmlspecialchars($_GET['make']) : ''; ?>">
            <input type="text" name="model" placeholder="Search by Model" value="<?php echo isset($_GET['model']) ? htmlspecialchars($_GET['model']) : ''; ?>">
            <input type="text" name="year" placeholder="Search by Year" value="<?php echo isset($_GET['year']) ? htmlspecialchars($_GET['year']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <h2>Car Listings</h2>

    <?php
    include '../controller/CarController.php';

    // Handle search query parameters
    $carController = new CarController();
    $filters = [];
    if (isset($_GET['make']) && !empty($_GET['make'])) {
        $filters['make'] = $_GET['make'];
    }
    if (isset($_GET['model']) && !empty($_GET['model'])) {
        $filters['model'] = $_GET['model'];
    }
    if (isset($_GET['year']) && !empty($_GET['year'])) {
        $filters['year'] = $_GET['year'];
    }
    $cars = $carController->getCarListings($filters);
    ?>

    <?php if (!empty($cars)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Color</th>
                    <th>Price</th>
                    <th>Transmission</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['car_id']); ?></td>
                        <td><?php echo htmlspecialchars($car['make']); ?></td>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                        <td><?php echo htmlspecialchars($car['color']); ?></td>
                        <td><?php echo htmlspecialchars($car['price']); ?></td>
                        <td><?php echo htmlspecialchars($car['transmission']); ?></td>
                        <td><?php echo htmlspecialchars($car['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="color: white;">No car listings found.</p>
    <?php endif; ?>
</body>

</html>
