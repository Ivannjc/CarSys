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
        <a href="viewReviews.php">View Car Reviews</a>
        <a href="sellerSavedCars.php">View Saved Cars</a>


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

    session_start();
    $created_by = $_SESSION['email']; // Use email as created_by
    $carController = new CarController();

    $filters = [];
    if (isset($_GET['make'])) $filters['make'] = $_GET['make'];
    if (isset($_GET['model'])) $filters['model'] = $_GET['model'];
    if (isset($_GET['year'])) $filters['year'] = $_GET['year'];

    $cars = $carController->getCarListings($created_by, $filters);
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
                        <th>Description</th>
                        <th>Number of Views</th> <!-- New column for views -->
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
                                <input type="number" name="price[<?php echo $car['car_id']; ?>]" value="<?php echo htmlspecialchars($car['price']); ?>">
                            </td>
                            <td>
                                <input type="text" name="description[<?php echo $car['car_id']; ?>]" value="<?php echo htmlspecialchars($car['description']); ?>">
                            </td>
                            <td>
                                <!-- Display the number of views -->
                                <?php echo htmlspecialchars($car['view_count']); ?>
                            </td>
                            <td>
                                <button type="submit" class="delete-button" name="delete" value="<?php echo $car['car_id']; ?>">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="updateCarList" name="update">Update</button>
        <?php else: ?>
            <h2>No cars found.</h2>
        <?php endif; ?>
    </form>


</body>

</html>