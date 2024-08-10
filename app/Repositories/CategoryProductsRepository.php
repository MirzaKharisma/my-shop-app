<?php

namespace App\Repositories;

use App\Models\CategoryProducts;

class CategoryProductsRepository{
    protected $categoryProductsModel;

    public function __construct(CategoryProducts $categoryProductsModel) {
        $this->categoryProductsModel = $categoryProductsModel;
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''){
        $category = $this->categoryProductsModel->query();

        if(!empty($filter['name'])){
            $category->where('name', 'LIKE', '%' . $filter['name'] . '%');
        }

        $sort = $sort ?: 'id DESC';
        $category->orderByRaw($sort);
        $itemPerPage = ($itemPerPage > 0) ? $itemPerPage : false;

        return $category->paginate($itemPerPage)->appends('sort', $sort);
    }

    public function getById(int $id){
        return $this->categoryProductsModel->query()->find($id);
    }

    public function store(array $payload){
        return $this->categoryProductsModel->query()->create($payload);
    }

    public function edit(array $payload, int $id){
        return $this->categoryProductsModel->query()->find($id)->update($payload);
    }

    public function drop(int $id){
        return $this->categoryProductsModel->query()->find($id)->delete($id);
    }
}
