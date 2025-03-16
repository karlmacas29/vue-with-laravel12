<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $products = Product::find($id);

        $products->name = $request->name;
        $products->description = $request->description;
        if($request->image != $products->image){
             // Extract the image extension from the Base64 string
             $strpos = strpos($request->image, ';');
             $sub = substr($request->image, 0, $strpos);
             $extension = explode('/', $sub)[1];
 
             // Generate a unique filename
             $name = time() . "." . $extension;
 
             // Decode the Base64 string
             $imageData = substr($request->image, strpos($request->image, ',') + 1);
             $imageData = base64_decode($imageData);
 
             // Create an image instance and resize it
             $img = Image::make($imageData)->resize(200, 200);
 
             // Define upload path and save the image
             $upload_path = public_path('/upload/');
             $image = $upload_path . $products->image;
             if (file_exists($image)) {
                 @unlink($image);
             }
             $img->save($upload_path . $name);
 
             // Store image filename in the database
             $products->image = $name;
        }else{
            $products->image = $products->image;
        }
        $products->type = $request->type;
        $products->quantity = $request->quantity;
        $products->price = $request->price;
        $products->save();

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
        $request->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        $product = new Product();

        $product->name = $request->name;
        $product->description = $request->description;
        if(!empty($request->image)){
             // Extract the image extension from the Base64 string
             $strpos = strpos($request->image, ';');
             $sub = substr($request->image, 0, $strpos);
             $extension = explode('/', $sub)[1];
 
             // Generate a unique filename
             $name = time() . "." . $extension;
 
             // Decode the Base64 string
             $imageData = substr($request->image, strpos($request->image, ',') + 1);
             $imageData = base64_decode($imageData);
 
             // Create an image instance and resize it
             $img = Image::make($imageData)->resize(200, 200);
 
             // Define upload path and save the image
             $upload_path = public_path('/upload/');
             if (!file_exists($upload_path)) {
                 mkdir($upload_path, 0777, true); // Create directory if not exists
             }
             $img->save($upload_path . $name);
 
             // Store image filename in the database
             $product->image = $name;
        }else{
            $product->image = "no-image.png";
        }
        $product->type = $request->type;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();
    }

}
