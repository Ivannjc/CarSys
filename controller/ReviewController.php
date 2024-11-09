<?php
session_start();
include '../entity/Review.php';

// Ensure the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

// Check if the request is a POST request and contains the required data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['review_text'])) {
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $review_text = $_POST['review_text'];

    // Create a new Review instance and attempt to add the review
    $review = new Review();
    $result = $review->addReview($user_id, $car_id, $review_text);

    // Check if the review was successfully added
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review. Please check database connection or syntax errors in addReview method.']);
    }
} else {
    // If the request is invalid, return an error message
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
