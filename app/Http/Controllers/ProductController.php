<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $search = $request->query('search');
            $categoryId = $request->query('category_id');
            if ($categoryId) {
                $products = Product::where('name', 'like', '%' . $search . '%')
                    ->where('category_id', $categoryId)
                    ->get();
            } else {
                $products = Product::where('name', 'like', '%' . $search . '%')
                    ->get();
            }
            $products->load('category');
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request -> validate([
                "name" => "required|unique:products|max:255",
                "price" => "required",
                "description" => "required",
                "link_url" => "required|string",
                'category_id' => 'required|string',
                'is_active' => 'required|boolean',
            ]);
            $product = Product::create($request->all());
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id) {
        try {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->price = $request->price;
            $product->description = $request->description;
            $product->link_url = $request->link_url;
            $product->category_id = $request-> category_id;
            $product->is_active = $request->is_active;
            $product->save();
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id) {
        try {
            $product = Product::find($id);
            $product->delete();
            return response()->json(['message' => 'Produto foi Deletado com Sucesso'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $product = Product::find($id);
            return response()->json($product, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // public function search(Request $request)
    // {
    //     try {
    //         $search = $request->query('search');
    //         $categoryId = $request->query('category_id');
    //         if ($categoryId) {
    //             $products = Product::where('name', 'like', '%' . $search . '%')
    //                 ->where('category_id', $categoryId)
    //                 ->get();
    //         } else {
    //             $products = Product::where('name', 'like', '%' . $search . '%')
    //                 ->get();
    //         }
    //         return response()->json($products, 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => $e->getMessage()], 500);
    //     }
    // }
    public function searchPerCategory($id)
    {
        try {
            $products = Product::where('category_id', $id)->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
