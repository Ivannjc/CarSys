<?php
include '../controller/LoanCalcController.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Loan Calculator</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-image: url('https://digitalsynopsis.com/wp-content/uploads/2014/06/supercar-wallpapers-bugatti-1.jpg');
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
            /* Set all body text to white */
        }

        .loan-calculator-container {
            background-color: rgba(0, 0, 0, 0.75);
            /* Darker background for contrast */
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .loan-calculator-container h2 {
            color: #ffffff;
            font-size: 28px;
            /* Slightly larger font */
            margin-bottom: 20px;
        }

        .loan-label {
            color: #ffffff;
            font-size: 16px;
            /* Adjusted for readability */
            display: block;
            margin: 10px 0 5px;
            /* Consistent spacing */
        }

        input[type="number"] {
            width: 90%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #333;
            /* Darker input background */
            color: white;
            /* White text inside inputs */
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        h3,
        p {
            color: #ffffff;
            /* White for result text */
            font-size: 18px;
        }
    </style>
</head>

<body>
    <div class="navbar">
    <a href="buyerCarListings.php">View Car Listings</a>
        <a href="savedCars.php">View Saved Cars</a>
        <a href="loanCalculator.php">Loan Calculator</a>
        <a href="buyerAgentAccounts.php">View Agents</a>
        <a href="buyerAgentReviews.php">View Reviews</a>

        <form action="../controller/logoutController.php" method="POST" style="display:inline;">
            <button class="logout-button" type="submit">Logout</button>
        </form>
    </div>
    <div class="loan-calculator-container">
        <h2>Car Loan Calculator</h2>

        <form action="../controller/LoanCalcController.php" method="POST" id="loanCalculatorForm">
            <label for="loanAmount" class="loan-label">Loan Amount:</label>
            <input type="number" name="loanAmount" id="loanAmount" required placeholder="Enter loan amount" />
            <br>

            <label for="interestRate" class="loan-label">Interest Rate (% per year):</label>
            <input type="number" name="interestRate" id="interestRate" step="0.1" required placeholder="Enter interest rate" />

            <br>
            <label for="loanTerm" class="loan-label">Loan Term (years):</label>
            <input type="number" name="loanTerm" id="loanTerm" required placeholder="Enter loan term in years" />
            <br>
            <button type="submit">Calculate</button>
        </form>

        <?php
        if (isset($_GET['monthlyPayment'])) {
            $monthlyPayment = $_GET['monthlyPayment'];
            $totalAmount = $_GET['totalAmount'];
            $totalInterest = $_GET['totalInterest'];
            echo "<h3>Results:</h3>";
            echo "<p>Monthly Payment: $" . number_format($monthlyPayment, 2) . "</p>";
            echo "<p>Total Amount Paid: $" . number_format($totalAmount, 2) . "</p>";
            echo "<p>Total Interest: $" . number_format($totalInterest, 2) . "</p>";
        }
        ?>
    </div>
</body>

</html>