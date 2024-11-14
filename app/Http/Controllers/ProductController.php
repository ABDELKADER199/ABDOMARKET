<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product = product::all();
        if ($product->isEmpty()) {
            $response = [
                "message" => "NO Records Found",
                "Status" => "200"

            ];
        } else {
            $response = [
                "message" => "Get All Successfully",
                "Status" => "200",
                "Data" => $product
            ];
        }
        return response($response, 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'products_name' => 'required|string',
            'product_description' => 'required|string',
            'parcode' => 'required|string',
            'branch' => 'required|string',
            'salary' => 'required|numeric',
            'products_image' => 'required|file'
        ]);

        $product = new Product();
        $product->products_name = $request->products_name;
        $product->product_description = $request->product_description;
        $product->parcode = $request->parcode;
        $product->branch = $request->branch;
        $product->salary = $request->salary;


        $image_data = $request->file('products_image');
        $image_name = time() . '_' . $image_data->getClientOriginalName();
        $location = public_path('Product');
        $image_data->move($location, $image_name);
        $file_path = 'Product/' . $image_name;

        $product->products_image = $file_path;

        $product->save();
        $response = [
            "message" => "Create New Items Successfully",
            "Status" => 200,
            "Data" => $product
        ];
        return response($response, 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if ($product == null) {
            $response = [
                "message" => "THIS Product Not Found",
                "status" => 200
            ];
        } else {
            $response = [
                "message" => "List Items Successfully",
                "Status" => 200,
                "Data" => $product
            ];
        }

        return response($response, 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'products_name' => 'required|string',
            'product_description' => 'required|string',
            'parcode' => 'required|string',
            'branch' => 'required|string',
            'salary' => 'required|numeric',
            'products_image' => 'file'
        ]);

        $product = Product::find($id);
        $product->products_name = $request->products_name;
        $product->product_description = $request->product_description;
        $product->parcode = $request->parcode;
        $product->branch = $request->branch;
        $product->salary = $request->salary;


        $image_data = $request->file('products_image');

        if ($image_data == null) {
            $file_path = $product->products_image;
        } else {
            $image_name = time() . '_' . $image_data->getClientOriginalName();
            $location = public_path('Product');
            $image_data->move($location, $image_name);
            $file_path = 'Product/' . $image_name;
            $oldPath = public_path($product->products_image);
            unlink($oldPath);
        }

        $product->products_image = $file_path;

        $product->save();
        $response = [
            "message" => "Updated  Item Successfully",
            "Status" => 200,
            "Data" => $product
        ];
        return response($response, 200);
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        
        if ($product == null) {
            return response([
                "message" => "THIS Product Not Found",
                "status" => 404
            ], 404);
        }

        $image_path = public_path($product->products_image);
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $product->delete();

        return response([
            "message" => "Product Deleted Successfully",
            "status" => 200
        ], 200);
    }
}
