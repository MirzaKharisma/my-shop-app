<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductCollection;
use App\Services\ProductsService;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    private $productsService;

    public function __construct(ProductsService $productsService) {
        $this->productsService = $productsService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = [
            'name' => $request->name ?? ''
        ];

        $products = $this->productsService->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->json([
            "message" => "All Products Retrived Successfully",
            "data" => new ProductCollection($products['data'])
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        try{
            if(isset($request->validator) && $request->validator->fails()){
                return response()->json([
                    "message" => $request->validator->errors()
                ], 404);
            }

            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            $product = $this->productsService->create([
                "name" => $request->name,
                "product_category_id" => $request->product_category_id,
                "price" => $request->price,
                "image" => $imageName
            ]);

            Storage::disk('public')->put($imageName, $request->image->get());

            if(!$product){
                return response()->json([
                    "message" => $product['error']
                ], 400);
            }
            return response()->json([
                "message" => "Product created successfully",
                "data" => $product['data']
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productsService->getById($id);

        if (!($product['status'])) {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }

        return response()->json([
            "message" => "Product retrived successfully",
            "data" => $product['data']
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try{
            if(isset($request->validator) && $request->validator->fails()){
                return response()->json([
                    "message" => $request->validator->errors()
                ], 404);
            }

            $imageName = Str::random(32).".".$request->image->getClientOriginalExtension();

            $product = $this->productsService->update([
                "id" => $id,
                "name" => $request->name,
                "product_category_id" => $request->product_category_id,
                "price" => $request->price,
                "image" => $imageName
            ]);

            Storage::disk('public')->put($imageName, $request->image->get());

            if(!$product){
                return response()->json([
                    "message" => $product['error']
                ], 400);
            }

            return response()->json([
                "message" => "Product updated successfully",
                "data" => $product['data']
            ], 201);
        }catch(Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->productsService->delete($id);

        if (!$product) {
            return response()->json([
                "message" => "Product not found"
            ], 404);
        }

        return response()->json([
            "message" => "Product deleted successfully"
        ], 200);
    }
}
