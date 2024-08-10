<?php

namespace App\Services;

use App\Repositories\ProductsRepository;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductsService{
    private $productRepository;

    public function __construct(ProductsRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    public function create(array $payload){
        try{
            $product = $this->productRepository->store($payload);

            return [
                'status' => true,
                'data' => $product
            ];
        }catch(Throwable $th){
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''){
        $product = $this->productRepository->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $product
        ];
    }

    public function getById(int $id): array {
        $category = $this->productRepository->getById($id);
        if(empty($category)){
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $category
        ];
    }

    public function update(array $payload): array
    {
        try {

            $this->productRepository->edit($payload, $payload['id']);

            $category = $this->getById($payload['id']);

            return [
                'status' => true,
                'data' => $category['data']
            ];
        } catch (Throwable $th) {
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }


    public function delete(int $id): bool
    {
        try {
            $this->productRepository->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }
}
