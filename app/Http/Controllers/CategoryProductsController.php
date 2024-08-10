<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Services\CategoryProductsService;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="L5 OpenApi",
 *      description="L5 Swagger OpenApi description",
 *      @OA\Contact(
 *          email="darius@matulionis.lt"
 *      ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="https://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/",
 *     description="Home page",
 *     @OA\Response(response="default", description="Welcome page")
 * )
 */
class CategoryProductsController extends Controller
{
    private $categoryProductsService;

    public function __construct(CategoryProductsService $categoryProductsService) {
        $this->categoryProductsService = $categoryProductsService;
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

        $categories = $this->categoryProductsService->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');

        return response()->json([
            "message" => "All Categories Retrived Successfully",
            "data" => new CategoryCollection($categories['data'])
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        if(isset($request->validator) && $request->validator->fails()){
            return response()->json([
                "message" => $request->validator->errors()
            ], 404);
        }

        $payload = $request->only(['name']);
        $category = $this->categoryProductsService->create($payload);
        if(!$category){
            return response()->json([
                "message" => $category['error']
            ], 400);
        }
        return response()->json([
            "message" => "Category product created successfully",
            "data" => $category['data']
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = $this->categoryProductsService->getById($id);

        if (!($category['status'])) {
            return response()->json([
                "message" => "Category product not found"
            ], 404);
        }

        return response()->json([
            "message" => "Category product retrived successfully",
            "data" => $category['data']
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->json([
                "message" => $request->validator->errors()
            ], 404);
        }

        $payload = $request->only(['name', 'id']);
        $category = $this->categoryProductsService->update($payload, $payload['id'] ?? 0);

        if(!$category){
            return response()->json([
                "message" => $category['error']
            ], 400);
        }

        return response()->json([
            "message" => "Category product updated successfully",
            "data" => $category['data']
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = $this->categoryProductsService->delete($id);

        if (!$category) {
            return response()->json([
                "message" => "Category product not found"
            ], 404);
        }

        return response()->json([
            "message" => "Category product deleted successfully"
        ], 200);
    }
}
