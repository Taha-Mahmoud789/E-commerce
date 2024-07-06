<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Index Method: Retrieves all products from the database.
    public function index()
    {

        $products = Product::with('inventory')
        ->latest()
        ->simplePaginate(10);
        // $products = Product::select('products.*', 'inventory.quantity')
        //     ->leftJoin('inventory', 'products.id', '=', 'inventory.product_id')
        //     ->latest()
        //     ->simplePaginate(10);
        return response()->json(['message' => 'successfully', 'products' => $products]);
    }

    // Create   Product
    public function create(StoreProductRequest $request)
    {
        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                $imagePath = time() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public/images', $imagePath); // Store image in storage/app/public/images
                $imagePath = '/storage/images/' . $imagePath; // Publicly accessible path
            } else {
                return response()->json(['message' => 'Invalid image file'], 400);
            }
        }

        // Create product
        $productData = $request->only(['name', 'description', 'price', 'category_id', 'brand', 'size', 'color']);
        $productData['image'] = $imagePath;
        $product =  Product::create($productData);

        $Inventory = Inventory::create([
            'product_id' => $product->id,
            'quantity' => $request->input('quantity'),
        ]);

        return response()->json(['message' => 'Product Create successfully', 'product' => $product, "quantity" => $Inventory]);
    }


    // Modify a specific product

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $productData = $request->only(['name', 'description', 'price', 'category_id', 'brand', 'size', 'color']); 
        $product->update($productData);
        $inventory = Inventory::updateOrCreate(
            ['product_id' => $product->id],
            ['quantity' => $request->input('quantity')]
        );
        return response()->json(['message' => 'Product updated successfully', 'product' => $product, 'inventory' => $inventory]);
    }

public function updateImage(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('images', 'public');

            // Delete old image if it exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->image = $filePath;
            $product->save();
        }

        return response()->json(['message' => 'Product image updated successfully', 'product' => $product]);
    }



    // Show Method: Retrieves a specific product by its ID.
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $inventory = Inventory::where('product_id', $id)->first();

        // Check if the product exists and if inventory is available
        if ($product && $inventory) {
            if ($inventory->quantity > 0) {
                return response()->json([
                    'message' => 'Product is available.',
                    'product' => $product,
                    'quantity' => $inventory->quantity
                ]);
            } else {
                return response()->json(['message' => 'Product is out of stock.'], 404);
            }
        } else {
            return response()->json(['message' => 'Product not found.'], 404);
        }
    }

    //  A specific product is removed and placed in the trash
    public function softdelete($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }

    // A specific product is removed 
    public function forceDelete($id)
    {
        $product = Product::withTrashed()->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->forceDelete();
        return response()->json(['message' => 'Product permanently deleted successfully']);
    }

    // Return a specific product from the trash
    public function restore($id)
    {
        $product = Product::withTrashed()->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        $product->restore();
        return response()->json(['message' => 'Product restored successfully']);
    }
}
