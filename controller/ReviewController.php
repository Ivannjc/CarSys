<?php
session_start();
include '../entity/Review.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['car_id'], $_POST['review_text'])) {
    $user_id = $_SESSION['user_id'];
    $car_id = $_POST['car_id'];
    $review_text = $_POST['review_text'];

    $review = new Review();
    $result = $review->addReview($user_id, $car_id, $review_text);

    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
?>
