<?php
// Include the controller to get access to $reviews
include '../controller/AgentReviewController.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agent Reviews</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body>
    <div class="navbar">
        <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <a href="agentAccounts.php">View Agents</a>
        <a href="agentReviews.php">View Reviews</a>
        <a href="sellerSavedCars.php">View Saved Cars</a>
        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Agent Reviews</h2>

    <?php if (empty($reviews)) : ?>
        <h2>No reviews found.</h2>
    <?php else : ?>
        <table class="review-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Review</th>
                    <th>Rating</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reviews as $review) : ?>
                    <tr>
                        <td><?= htmlspecialchars($review['username']) ?></td>
                        <td><?= htmlspecialchars($review['review']) ?></td>
                        <td><?= htmlspecialchars($review['rating']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>

</html>
