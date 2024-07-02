<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreCategoriesRequest;
use App\Http\Requests\UpdateCategorisRequest;

class CategoriesController extends Controller
{
    
    // Display a listing of the categories
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    // Store a newly created category in storage
    public function store(StoreCategoriesRequest $request)
    {

        $category = Category::create([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }

    // Display the specified category
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Update the specified category in storage
    public function update(UpdateCategorisRequest $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $category = Category::findOrFail($id);
        $category->update([
            'name' => $request->input('name'),
        ]);

        return response()->json(['message' => 'Category updated successfully', 'category' => $category]);
    }

    // Remove the specified category from storage
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
