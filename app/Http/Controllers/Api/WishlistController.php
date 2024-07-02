<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Product;
use App\Models\Wishlist;
use App\Http\Requests\StoreWishlistRequest;

class WishlistController extends Controller
{

    /**
     * Display a listing of the user's wishlist items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = JWTAuth::parseToken()->authenticate();
        $wishlist = $user->wishlist()->with('product')->get();
        return response()->json(['wishlist' => $wishlist], 200);
    }

    /**
     * Store a newly created wishlist item in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreWishlistRequest $request)
    {

        $user = JWTAuth::parseToken()->authenticate();
        // Check if the product is already in the user's wishlist
        $existingWishlistItem = $user->wishlist()->where('product_id', $request->product_id)->first();

        if ($existingWishlistItem) {
            return response()->json(['message' => 'Product is already in your wishlist.'], 400);
        }

        // Add the product to the wishlist
        $wishlistItem = new Wishlist([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
        ]);

        $user->wishlist()->save($wishlistItem);

        // Get the product details
        $product = Product::find($request->product_id);

        return response()->json([
            'message' => 'Product added to wishlist successfully.',
            'wishlist_item' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
            ]
        ], 201);
    }

    /**
     * Remove the specified wishlist item from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $wishlist = $user->wishlist()->findOrFail($id);

        $wishlist->delete();

        return response()->json(['message' => 'Product removed from wishlist'], 200);
    }
}
