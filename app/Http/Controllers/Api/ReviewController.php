<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Review;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with('user', 'product')->get();
        // $products = Review::latest()->simplePaginate(5);
        return response()->json($reviews);
        if (!$reviews) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }



    public function store(StoreReviewRequest $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        // Check if the user has purchased the product
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereHas('items', function ($query) use ($request) {
                $query->where('product_id', $request->input('product_id'));
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'You must purchase the product before leaving a review.'], 403);
        }

        // Check if the user has already reviewed this product
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $request->input('product_id'))
            ->first();

        if ($existingReview) {
            return response()->json(['message' => 'You have already reviewed this product.'], 403);
        }


        $product_id = $request->input('product_id');

        // Check if the user has purchased the product
        $hasPurchased = Order::where('user_id', $user->id)
            ->whereHas('items', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'You can only review products you have purchased.'], 403);
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'rating' => $request->input('rating'),
            'review_text' => $request->input('review_text'),
        ]);

        return response()->json(['message' => 'Review created successfully', 'review' => $review]);
    }

    public function update(UpdateReviewRequest $request, $reviewId)
    {

        // Authenticate the user and get the user object
        $user = JWTAuth::parseToken()->authenticate();

        // Find the review
        $review = Review::findOrFail($reviewId);

        // Check if the authenticated user is the owner of the review
        if ($review->user_id !== $user->id) {
            return response()->json(['message' => 'You are not authorized to update this review.'], 403);
        }

        // Update the review
        $review->update([
            'rating' => $request->input('rating'),
            'review_text' => $request->input('review_text'),
        ]);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review]);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully']);
    }
    public function show($id)
    {
        $review = Review::with('user', 'product')->findOrFail($id);
        return response()->json($review);
    }
}
