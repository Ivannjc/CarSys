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
                window.location.href = '../boundary/buyerCarListings.php';
            }
        };
    </script>
</head>

<body>
    <div class="navbar">
        <a href="buyerCarListings.php">View Car Listings</a>
        <form action="buyerCarListings.php" method="GET" class="searchform">
            <input type="text" name="make" placeholder="Search by Make" value="<?php echo isset($_GET['make']) ? htmlspecialchars($_GET['make']) : ''; ?>">
            <input type="text" name="model" placeholder="Search by Model" value="<?php echo isset($_GET['model']) ? htmlspecialchars($_GET['model']) : ''; ?>">
            <input type="text" name="year" placeholder="Search by Year" value="<?php echo isset($_GET['year']) ? htmlspecialchars($_GET['year']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Car Listings</h2>

    <?php
    // Correct controller inclusion
    include '../controller/BuyerCarController.php';

    // Instantiate the controller to fetch car listings
    $buyerCarController = new BuyerCarController();

    $filters = [];
    if (isset($_GET['make'])) $filters['make'] = $_GET['make'];
    if (isset($_GET['model'])) $filters['model'] = $_GET['model'];
    if (isset($_GET['year'])) $filters['year'] = $_GET['year'];

    // Fetch the car listings based on the provided filters
    $cars = $buyerCarController->getAllCars($filters);
    ?>

    <?php if (!empty($cars)): ?>
        <table>
            <thead>
                <tr>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Year</th>
                    <th>Color</th>
                    <th>Price</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cars as $car): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($car['make']); ?></td>
                        <td><?php echo htmlspecialchars($car['model']); ?></td>
                        <td><?php echo htmlspecialchars($car['year']); ?></td>
                        <td><?php echo htmlspecialchars($car['color']); ?></td>
                        <td><?php echo htmlspecialchars($car['price']); ?></td>
                        <td><?php echo htmlspecialchars($car['description']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>No cars found.</h2>
    <?php endif; ?>

</body>

</html>
