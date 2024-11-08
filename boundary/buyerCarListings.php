<?php
session_start();
include '../controller/BuyerCarController.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: loginPage.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$buyerCarController = new BuyerCarController();

// Collect filters if they are set
$filters = [];
if (isset($_GET['make'])) $filters['make'] = $_GET['make'];
if (isset($_GET['model'])) $filters['model'] = $_GET['model'];
if (isset($_GET['year'])) $filters['year'] = $_GET['year'];

// Fetch the car listings based on the provided filters
$cars = $buyerCarController->getAllCars($filters);
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
            }
        };

        function viewDescription(car_id) {
            const descriptionDiv = document.getElementById(`description_${car_id}`);
            const viewCountElem = document.getElementById(`view_count_${car_id}`);

            // Toggle visibility of the description
            if (descriptionDiv.style.display === "none" || descriptionDiv.style.display === "") {
                // Show description
                fetch('../controller/CarController.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `view_description=1&car_id=${car_id}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        descriptionDiv.innerHTML = `<p>${data.description}</p>`;
                        descriptionDiv.style.display = "block";

                        // Update the view count displayed
                        const currentCount = parseInt(viewCountElem.textContent.split(": ")[1]) + 1;
                        viewCountElem.textContent = `Views: ${currentCount}`;
                    })
                    .catch(error => console.error('Error fetching description:', error));
            } else {
                // Hide description
                descriptionDiv.style.display = "none";
            }
        }
    </script>
</head>

<body>
    <div class="navbar">
        <a href="buyerCarListings.php">View Car Listings</a>
        <a href="savedCars.php">View Saved Cars</a>
        <a href="loanCalculator.php">Loan Calculator</a>
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
                    <th>Action</th>
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
                        <td>
                            <!-- Description field now inside the table -->
                            <button type="button" onclick="viewDescription(<?php echo $car['car_id']; ?>)">View Description</button>
                            <div id="description_<?php echo $car['car_id']; ?>" style="display: none;">
                                <p><?php echo htmlspecialchars($car['description']); ?></p>
                            </div>
                            <!-- <p id="view_count_<?php echo $car['car_id']; ?>">Views: <?php echo htmlspecialchars($car['view_count']); ?></p> -->
                        </td>

                        <td>
                            <form action="../controller/SaveCarController.php" method="POST">
                                <input type="hidden" name="car_id" value="<?php echo $car['car_id']; ?>">
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <h2>No cars found.</h2>
    <?php endif; ?>
</body>

</html>