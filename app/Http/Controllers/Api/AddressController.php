<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Models\Address;

class AddressController extends Controller
{
    // List all addresses for the authenticated user
    public function index()
    {
        $addresses = JWTAuth::parseToken()->authenticate()->addresses;
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['Addresses'=>$addresses ,'User'=> $user]);
    }

    // Add a new address
    public function store(StoreAddressRequest $request)
    {
        $address = JWTAuth::parseToken()->authenticate()->addresses()->create($request->all());
        $user = JWTAuth::parseToken()->authenticate();
        return response()->json(['message' => 'Address added successfully', 'address' => $address , 'User' => $user]);
    }

    // Get a specific address
    public function show($id)
    {
        $address = JWTAuth::parseToken()->authenticate()->addresses()->find($id);
        $user = JWTAuth::parseToken()->authenticate();
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        return response()->json(['message' => 'Address Found', 'address' => $address ,'User'=> $user]);
    }

    // Update an address
    public function update(UpdateAddressRequest $request, $id)
    {
        $address = JWTAuth::parseToken()->authenticate()->addresses()->find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        $address->update($request->all());
        return response()->json(['message' => 'Address updated successfully', 'address' => $address]);
    }

    // Delete an address
    public function destroy($id)
    {
        $address = JWTAuth::parseToken()->authenticate()->addresses()->find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 404);
        }
        $address->delete();
        return response()->json(['message' => 'Address deleted successfully']);
    }
}
