<?php
session_start();
?>
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

        // JavaScript for displaying popup form and submitting review
        function openReviewForm(car_id) {
            document.getElementById("reviewForm").style.display = "block";
            document.getElementById("car_id").value = car_id;
        }

        function closeReviewForm() {
            document.getElementById("reviewForm").style.display = "none";
        }

        // JavaScript to submit the review form data to the controller
        function submitReview(event) {
            event.preventDefault(); // Prevent form from submitting traditionally

            const form = document.getElementById('reviewFormElement');
            const formData = new FormData(form); // Collects form data including car_id and review_text

            // Send review data to ReviewController.php using fetch
            fetch('../controller/ReviewController.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message || "Review submitted successfully!");
                        closeReviewForm(); // Close the review form after submission
                    } else {
                        alert(data.message || "Failed to submit review.");
                    }
                })
                .catch(error => {
                    alert("Review submitted successfully!");
                    console.error('Error:', error);
                });
        }
    </script>
    <style>
        /* Style for the review form buttons */
        #reviewForm button {
            padding: 5px 15px;
            margin: 5px 10px;
            font-size: 14px;
            width: auto;
        }

        /* Specific style for the submit button */
        #reviewForm button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Specific style for the cancel button */
        #reviewForm button[type="button"] {
            background-color: #f44336;
            color: white;
            border: none;
            cursor: pointer;
        }

        /* Add hover effect for both buttons */
        #reviewForm button:hover {
            opacity: 0.8;
        }

        /* Style the popup form */
        #reviewForm {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1000;
        }

        /* Style for overlay to dim the background */
        #overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        /* Style the close button */
        #reviewForm button[type="button"] {
            margin-top: 10px;
            background-color: #ff5c5c;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
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

        <div class="table-container">
            <h2>Car Listings</h2>

            <?php
            include '../controller/CarController.php';

            // $created_by = $_SESSION['email']; // Use email as created_by
            $carController = new CarController();

            $filters = [];
            if (isset($_GET['make'])) $filters['make'] = $_GET['make'];
            if (isset($_GET['model'])) $filters['model'] = $_GET['model'];
            if (isset($_GET['year'])) $filters['year'] = $_GET['year'];

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
                                <th>Description</th>
                                <th>Number of Views</th>
                                <th>Review Car</th>
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
                                        <?php echo htmlspecialchars($car['view_count']); ?>
                                    </td>
                                    <td>
                                        <button type="button" onclick="openReviewForm(<?php echo $car['car_id']; ?>)">Review</button>
                                    </td>
                                    <td>
                                        <button type="submit" class="delete-button" name="delete" value="<?php echo $car['car_id']; ?>">Delete</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                    <button type="submit" class="updateCarList" name="update">Update</button>
                <?php else: ?>
                    <h2>No cars found.</h2>
                <?php endif; ?>
            </form>
        </div>

        <!-- Review Form Popup (separate from the main form) -->
        <div id="reviewForm" style="display: none;">
            <form id="reviewFormElement" onsubmit="submitReview(event)">
                <h3>Write a Review</h3>
                <input type="hidden" name="car_id" id="car_id">
                <textarea name="review_text" placeholder="Write your review here..." required></textarea>
                <br>
                <button type="submit">Submit</button>
                <br>
                <button type="button" onclick="closeReviewForm()">Cancel</button>
            </form>
        </div>
    </div>
</body>

</html>