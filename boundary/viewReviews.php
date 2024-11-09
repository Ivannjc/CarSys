<?php
include '../controller/ViewReviewController.php';

$controller = new ViewReviewController();
$reviews = $controller->getReviews();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Car Reviews</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
    <h2>Car Reviews</h2>
    <?php if (empty($reviews)) : ?>
        <h2>No reviews found.</h2>
    <?php else : ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Color</th>
                <th>Review</th>
            </tr>
            <?php foreach ($reviews as $review) : ?>
                <tr>
                    <td><?= htmlspecialchars($review['user_name']) ?></td>
                    <td><?= htmlspecialchars($review['make']) ?></td>
                    <td><?= htmlspecialchars($review['model']) ?></td>
                    <td><?= htmlspecialchars($review['year']) ?></td>
                    <td><?= htmlspecialchars($review['color']) ?></td>
                    <td><?= htmlspecialchars($review['review_text']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>

</html>