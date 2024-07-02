<?php

namespace App\Http\Controllers\Api;

use App\Models\Coupon;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoponRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CouponController extends Controller
{
    // Get all coupons

    public function index()
    {
        $coupons = Coupon::all();
        return response()->json(['message' => 'Successfully retrieved coupons', 'coupons' => $coupons]);
    }
    // Create a new coupon

    public function store(StoreCoponRequest $request)
    {

        $coupon = Coupon::create($request->all());

        return response()->json(['message' => 'Coupon created successfully', 'coupon' => $coupon]);
    }
    // Get a single coupon

    public function show($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        return response()->json(['message' => 'Successfully retrieved coupon', 'coupon' => $coupon]);
    }
    // Update a coupon

    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }
        $coupon->update($request->all());

        return response()->json(['message' => 'Coupon updated successfully', 'coupon' => $coupon]);
    }
    // Delete a coupon

    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        if (!$coupon) {
            return response()->json(['message' => 'Coupon not found'], 404);
        }

        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully']);
    }
}
