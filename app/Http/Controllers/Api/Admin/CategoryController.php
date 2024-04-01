<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'status' => 'success',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $category = Category::create([
            'name' => $request->name
        ]);
        return response()->json([
            'status' => 'success',
            'message' => 'category created successfully',
            'category' => $category,
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $category = Category::find($request->category_id);
            $category->name = $request->name;
            $category->save();

            return response()->json([
                'status' => 'success',
                'message' => 'category updated successfully',
                'category' => $category,
            ]);

    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        $category = Category::find($request->category_id);
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'category deleted successfully',
                'category' => $category,
            ]);

    }
}
