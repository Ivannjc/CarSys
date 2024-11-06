<?php
include '../controller/SaveCarController.php';

$user_id = $_SESSION['user_id'];
$controller = new SaveCarController();
$savedCars = $controller->getSavedCars($user_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Saved Cars</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>
<body>
    <div class="navbar">
        <a href="buyerCarListings.php">View Car Listings</a>
        <a href="savedCars.php">View Saved Cars</a>
        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Saved Cars</h2>

    <?php if (!empty($savedCars)): ?>
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
                <?php foreach ($savedCars as $car): ?>
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
        <h2>No saved cars found.</h2>
    <?php endif; ?>
</body>
</html>
