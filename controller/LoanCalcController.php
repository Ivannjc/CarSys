<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the form
    $loanAmount = $_POST['loanAmount'];
    $interestRate = $_POST['interestRate'];
    $loanTerm = $_POST['loanTerm'];

    // Calculate the car loan details
    $result = calculateCarLoan($loanAmount, $interestRate, $loanTerm);

    // Redirect back to loanCalculator.php with the results as query parameters
    header("Location: loanCalculator.php?monthlyPayment=" . $result['monthlyPayment'] . "&totalAmount=" . $result['totalAmount'] . "&totalInterest=" . $result['totalInterest']);
    exit();
}

function calculateCarLoan($loanAmount, $interestRate, $loanTerm) {
    // Convert interest rate to a monthly rate
    $monthlyRate = ($interestRate / 100) / 12;
    
    // Number of monthly payments (loan term in months)
    $numberOfPayments = $loanTerm * 12;

    // Calculate monthly payment using the formula
    if ($monthlyRate == 0) {
        $monthlyPayment = $loanAmount / $numberOfPayments;
    } else {
        $monthlyPayment = $loanAmount * $monthlyRate / (1 - pow(1 + $monthlyRate, -$numberOfPayments));
    }

    // Calculate total amount paid and total interest
    $totalAmount = $monthlyPayment * $numberOfPayments;
    $totalInterest = $totalAmount - $loanAmount;

    // Return the result
    return [
        'monthlyPayment' => $monthlyPayment,
        'totalAmount' => $totalAmount,
        'totalInterest' => $totalInterest
    ];
}
?>
