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
                window.location.href = '../boundary/carListings.php'; 
            }
        };
    </script>
</head>

<body>
    <div class="navbar">
        <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <form action="carListings.php" method="GET" class="searchform">
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
    include '../controller/CarController.php';

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

    <form action="../controller/CarController.php" method="POST">
        <?php if (!empty($cars)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Make</th>
                        <th>Model</th>
                        <th>Year</th>
                        <th>Color</th>
                        <th>Price</th>
                        <th>Transmission</th>
                        <th>Description</th>
                        <th>Delete Car Listing</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cars as $car): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($car['make']); ?></td>
                            <td><?php echo htmlspecialchars($car['model']); ?></td>
                            <td><?php echo htmlspecialchars($car['year']); ?></td>
                            <td><?php echo htmlspecialchars($car['color']); ?></td>
                            <td>
                                <input type="number" name="price[<?php echo $car['car_id']; ?>]" value="<?php echo htmlspecialchars($car['price']); ?>" required>
                            </td>
                            <td>
                                <select name="transmission[]" disabled>
                                    <option value="Automatic" <?php echo htmlspecialchars($car['transmission']) == 'Automatic' ? 'selected' : ''; ?>>Automatic</option>
                                    <option value="Manual" <?php echo htmlspecialchars($car['transmission']) == 'Manual' ? 'selected' : ''; ?>>Manual</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="description[<?php echo $car['car_id']; ?>]" value="<?php echo htmlspecialchars($car['description']); ?>" required>
                            </td>
                            <td>
                                <form action="../controller/CarController.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="car_id" value="<?php echo htmlspecialchars($car['car_id']); ?>">
                                    <button type="submit" name="delete" class="delete-button">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <input type="hidden" name="update" value="1">
            <button type="submit" class="updateCarList">Update</button>
        <?php else: ?>
            <p style="color: white;">No car listings found.</p>
        <?php endif; ?>
    </form>
</body>

</html>
