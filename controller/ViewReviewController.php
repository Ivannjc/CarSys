<?php
include '../entity/Review.php';

class ViewReviewController
{
    private $review;

    public function __construct()
    {
        $this->review = new Review();
    }

    // Function to retrieve reviews
    public function getReviews()
    {
        return $this->review->getAllReviews();
    }
}
