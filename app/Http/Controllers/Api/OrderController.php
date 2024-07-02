<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Inventory;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\StoreOrderRequest;




class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
      
        // Check product quantities
        foreach ($request->input('items') as $item) {
            $inventory = Inventory::where('product_id', $item['product_id'])->first();

            if (!$inventory || $inventory->quantity < $item['quantity']) {
                return response()->json(['message' => 'Product is out of stock or insufficient quantity for product ID ' . $item['product_id']], 400);
            }
        }

        // Create the order
          $user = JWTAuth::parseToken()->authenticate();
        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name, // Get name from authenticated user
            'customer_email' => $user->email, // Get email from authenticated user
            'total_amount' => 0, // Calculate this based on items
            'status' => 'pending', // or any initial status
        ]);

        // Add order items and update inventory
        foreach ($request->input('items') as $item) {
            $product = Product::findOrFail($item['product_id']);

            $orderItem = new OrderItem([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'unit_price' => $product->price, // Assuming price comes from product details
            ]);

            $order->items()->save($orderItem);

            // Update total amount of the order
            $order->total_amount += $orderItem->unit_price * $orderItem->quantity;

            // Update inventory
            $inventory = Inventory::where('product_id', $item['product_id'])->first();
            $inventory->quantity -= $orderItem->quantity;
            $inventory->save();
        }

        // Save the total amount after all items are added
        $order->save();

        return response()->json(['message' => 'Order created successfully', 'order' => $order]);
    }
    
    /////////////////////////////////////

    public function restore($orderId)
    {
        // Find the order
        $order = Order::findOrFail($orderId);

        // Iterate over the order items
        foreach ($order->items as $item) {
            // Find the inventory record for the product
            $inventory = Inventory::where('product_id', $item->product_id)->first();

            if ($inventory) {
                // Restore the quantity
                $inventory->quantity += $item->quantity;
                $inventory->save();
            }
        }

        // Update the order status to 'restored' or any appropriate status
        $order->status = 'restored';
        $order->save();

        return response()->json(['message' => 'Inventory restored successfully', 'order' => $order]);
    }
}
