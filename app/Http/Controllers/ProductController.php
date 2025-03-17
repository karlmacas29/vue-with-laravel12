<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{   
    //delete
    public function destroy($id){
        $products = Product::findOrFail($id);
        $upload_path = public_path('/upload/');
        $image = $upload_path . $products->image;
        if (file_exists($image)) {
            @unlink($image);
        }
        $products->delete();
    }
    //update 
    public function update(Request $request, $id){
        // Find product (return 404 if not found)
    $product = Product::findOrFail($id);

    // // Validate input fields
    // $request->validate([
    //     'name' => 'required|string',
    //     'description' => 'required|string',
    //     'image' => 'nullable|image|mimes:jpg,png|max:2048',
    //     'type' => 'nullable|string',
    //     'quantity' => 'nullable|string',
    //     'price' => 'nullable|string',
    // ]);

    // Update product details
    $product->name = $request->name;
    $product->description = $request->description;
    $product->type = $request->type;
    $product->quantity = $request->quantity;
    $product->price = $request->price;

    // Ensure 'upload' directory exists
    if (!file_exists(public_path('upload'))) {
        mkdir(public_path('upload'), 0777, true);
    }

    // Handle new image upload (if provided)
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'upload/' . $fileName;

        // Delete the old image (if it exists and is not the default one)
        if ($product->image && $product->image !== "no-image.png") {
            $oldImagePath = public_path($product->image);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Move the new image
        $file->move(public_path('upload'), $fileName);
        $product->image = $filePath;
    }

    $product->save();

    // Return success response
    return response()->json([
        'message' => 'Product updated successfully',
        'product' => $product
    ], 200);

    }
    //edit data (it will return that match the id)
    public function edit($id){
        $products = Product::find($id);
        return response()->json(
            [
                'products' => $products
            ],200
        );
    }
    //get data structure
    public function index(Request $request){
        $products = Product::query();
        //search Query
        //add a Request $request in function index()
        //add a conditional like this
        if($request->searchQuery != ''){
            $products = Product::where('name','like','%'.$request->searchQuery . '%');
        }
        //else is this will return
        
        $products = $products->latest()->paginate(5);
        //get() is to get all data
        //paginate(2) how many data you return?
        // if 2 then 2 data will return and the next page has another 2 data 

        return response()->json([
            'products' => $products
        ],200);
    }

    //submit data structure
    public function store(Request $request){
        //validation
        //it will pass a error code if validation met
        // Validate input fields
    $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'image' => 'nullable|image|mimes:jpg,png|max:2048',
        'type' => 'nullable|string',
        'quantity' => 'nullable|string',
        'price' => 'nullable|string',
    ]);

    $product = new Product();
    $product->name = $request->name;
    $product->description = $request->description;

    // Ensure the 'upload' directory exists
    if (!file_exists(public_path('upload'))) {
        mkdir(public_path('upload'), 0777, true);
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        $file = $request->file('image'); // Correct field name
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'upload/' . $fileName;
        $file->move(public_path('upload'), $fileName);
        $product->image = $filePath;
    } else {
        $product->image = "no-image.png"; // Default image
    }

    // Assign remaining fields
    $product->type = $request->type;
    $product->quantity = $request->quantity;
    $product->price = $request->price;

    $product->save();

    // Return a JSON response
    return response()->json([
        'message' => 'Product added successfully',
        'product' => $product
    ], 201);
    }

}
