<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Http\Controllers\Controller;
use App\Models\Store;

class ProductsController extends Controller
{
    // retrieving all products along with its specific store
    public function getAllProducts() {
        $products = Products::with("store")->get();
        
        return response()->json(['products' => $products]);
    }

    // get a certain product of a certain store using store id
    public function getMyProducts($id) {
        $product = Products::where('storeId', $id)->get();

        return response(['products' => $product]);
    }

    // retrieving all products of a the stores
    public function getStoreProducts($id) {
        $products = Products::where('storeId', $id)->with('store')->get();

        return response(['products' => $products]);
    }

    // adding products
    public function addProduct(Request $request) {
        // validations
        $request->validate([
            'productName' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:50|max:10000',
            'imageUrl' => 'required|max:2000000|image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        try {
            \DB::beginTransaction();
            $imageName = $request->imageUrl->getClientOriginalName(); // getting the original name of the image
            
            $add = Products::create([
                "productName" => $request->productName,
                "description" => $request->description,
                "price" => $request->price,
                "imageUrl" => $imageName,
                "storeId" => $request->storeId
            ]);
            \DB::commit();
            
            $request->imageUrl->move(public_path('images/uploads'), $imageName);
            
            // parameter to be change
            // needs parameter route
            return response(['success', "Successfully added!"],200);
        } catch (\Exception $e) {
            \DB::rollback();
            return response(['error',"Cannot add : ".$e],500);
        }
    }

    // editing a certain product/item
    public function editProduct(Request $request) {
        $request->validate([
            'productName' => 'required|max:255',
            'description' => 'required|max:255',
            'price' => 'required|numeric|min:50|max:10000',
            'imageUrl' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        try {

            \DB::beginTransaction();
            $imageName = $request->imageUrl->getClientOriginalName();
            Products::find($request->id)
                    ->update([
                        'productName' => $request->productName,
                        'description' => $request->description,
                        'price' => $request->price,
                        'imageUrl' => $imageName,
                    ]);
            \DB::commit();
            $request->imageUrl->move(public_path('images/uploads'), $imageName);
            return response()->json(['success', "Successfully updated!"],200);

        } catch(\Exception $e) {
            \DB::rollback();
            return response()->json('Error : ', $e);
        }
    }

    // deleting a product/item
    public function deleteProduct($id) {
        try {
            \DB::beginTransaction();
                Products::findOrFail($id)->delete();
            \DB::commit();
            return response(['success' => "Deleted successfully!"],200);
        } catch(\Exception $e) {
            \DB::rollback();
            return response()->json('Error : ' , $e);
        }
    }
}