<?php
// Include the controller if needed for some advanced logic or validation
include '../controller/LoanCalcController.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Loan Calculator</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="loan-calculator-container">
        <h2>Car Loan Calculator</h2>

        <form action="LoanCalcController.php" method="POST" id="loanCalculatorForm">
            <label for="loanAmount">Loan Amount:</label>
            <input type="number" name="loanAmount" id="loanAmount" required placeholder="Enter loan amount" />

            <label for="interestRate">Interest Rate (% per year):</label>
            <input type="number" name="interestRate" id="interestRate" step="0.1" required placeholder="Enter interest rate" />

            <label for="loanTerm">Loan Term (years):</label>
            <input type="number" name="loanTerm" id="loanTerm" required placeholder="Enter loan term in years" />

            <button type="submit">Calculate</button>
        </form>

        <?php
        // If there is a result, display it
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
