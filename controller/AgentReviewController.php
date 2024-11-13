<?php
session_start();
include '../entity/Review.php';
include '../entity/Agent.php';

// Check if the user is authenticated
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit();
}

class AgentReviewController
{
    public $review;

    public function __construct()
    {
        // Instantiate the Review class
        $this->review = new Review();
    }

    // Fetch reviews from the Review class
    public function getAgentReviews()
    {
        return $this->review->getAgentReviews();
    }

    // Handle POST request for submitting a review
    public function submitReview()
    {
        if (isset($_POST['username'], $_POST['review_text'], $_POST['rating'])) {
            $reviewer_id = $_SESSION['user_id'];
            $agent_username = $_POST['username'];
            $review_text = $_POST['review_text'];
            $rating = (int)$_POST['rating'];

            // Validate the rating input to ensure it’s between 1 and 5
            if ($rating < 1 || $rating > 5) {
                echo json_encode(['success' => false, 'message' => 'Rating must be between 1 and 5']);
                exit();
            }

            // Fetch agent's user ID by username from the Agent class
            $agent = new Agent();
            $agent_id = $agent->getUserByUsername($agent_username)['id'];

            if (!$agent_id) {
                echo json_encode(['success' => false, 'message' => 'Agent not found']);
                exit();
            }

            // Add the review to the database
            $result = $agent->addReview($reviewer_id, $agent_id, $review_text, $rating);

            if ($result) {
                echo json_encode(['success' => true, 'message' => 'Review submitted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to submit review.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
        }
    }
}

// Instantiate the controller
$controller = new AgentReviewController();

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->submitReview();
}

// Fetch reviews for the view
$reviews = $controller->getAgentReviews();
