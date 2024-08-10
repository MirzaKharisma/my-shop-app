<?php

namespace App\Services;

use App\Repositories\CategoryProductsRepository;
use Throwable;

class CategoryProductsService{
    private $categoryRepository;

    public function __construct(CategoryProductsRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }

    public function create(array $payload){
        try{
            $category = $this->categoryRepository->store($payload);

            return [
                'status' => true,
                'data' => $category
            ];
        }catch(Throwable $th){
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''){
        $categories = $this->categoryRepository->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $categories
        ];
    }

    public function getById(int $id): array {
        $category = $this->categoryRepository->getById($id);
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

    public function update(array $payload, int $id): array
    {
        try {
            $this->categoryRepository->edit($payload, $id);

            $category = $this->getById($id);

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
            $this->categoryRepository->drop($id);

            return true;
        } catch (Throwable $th) {
            return false;
        }
    }
}
