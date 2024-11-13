<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Agent Accounts</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
    <script>
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const message = urlParams.get('message');
            if (message) {
                alert(message);
                window.history.replaceState({}, document.title, 'agentAccounts.php'); // Clear message from URL
            }
        };

        function openReviewPopup(username) {
            document.getElementById("reviewForm").style.display = "block";
            document.getElementById("overlay").style.display = "block";
            document.getElementById("review_username").value = username;
        }

        function closeReviewPopup() {
            document.getElementById("reviewForm").style.display = "none";
            document.getElementById("overlay").style.display = "none";
        }
    </script>
    <style>
        /* Style for review popup */
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

        /* Overlay for dim background */
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

        /* Style for buttons in the form */
        #reviewForm button {
            padding: 5px 15px;
            margin: 5px 10px;
            font-size: 14px;
            cursor: pointer;
        }

        #reviewForm button[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
        }

        #reviewForm button[type="button"] {
            background-color: #f44336;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <div id="overlay" onclick="closeReviewPopup()"></div>
    <div class="navbar">
        <a href="carListings.php">View Car Listings</a>
        <a href="addCarListings.php">Create Car Listing</a>
        <a href="agentAccounts.php">View Agents</a>
        <a href="agentReviews.php">View Reviews</a>
        <a href="sellerSavedCars.php">View Saved Cars</a>
        <form action="agentAccounts.php" method="GET" class="searchform">
            <input type="text" name="username" placeholder="Search by username" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
            <button type="submit">Search</button>
        </form>
        <form action="../controller/LogoutController.php" method="POST" style="display:inline;" id="logoutButton" name="logoutButton">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>

    <h2>Agent Accounts</h2>

    <?php
    include '../controller/AgentAccController.php';

    if (!empty($userAccounts)) {
    ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Profile</th>
                    <th>Review Account</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userAccounts as $user): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                        <td><button type="button" onclick="openReviewPopup('<?php echo htmlspecialchars($user['username']); ?>')">Review</button></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p style="color: white;">No agent accounts found.</p>
    <?php } ?>

    <!-- Review Popup Form -->
    <div id="reviewForm">
        <form id="reviewFormElement" onsubmit="submitReview(event)">
            <h3>Write a Review for Agent</h3>
            <input type="hidden" name="username" id="review_username">
            <textarea name="review_text" placeholder="Write your review here..." required></textarea>
            <br>
            <label for="rating">Rating:</label>
            <select name="rating" id="rating" required>
                <option value="" disabled selected>Select a rating</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
            <br><br>
            <button type="submit">Submit</button>
            <button type="button" onclick="closeReviewPopup()">Cancel</button>
        </form>
    </div>

    <script>
        function submitReview(event) {
            event.preventDefault();
            const form = document.getElementById('reviewFormElement');
            const formData = new FormData(form);

            fetch('../controller/AgentReviewController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message || "Review submitted successfully!");
                    closeReviewPopup();
                } else {
                    alert(data.message || "Error submitting review.");
                    closeReviewPopup();

                }
            })
            .catch(error => {
                alert("Review submitted successfully!");
                console.error('Error:', error);
                closeReviewPopup();

            });
        }
    </script>
</body>

</html>
