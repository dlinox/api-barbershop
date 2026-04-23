<?php

namespace App\Modules\BarbershopPanel\Barbershop\Http\Controllers;

use App\Common\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use App\Modules\BarbershopPanel\Barbershop\Services\CategoryService;
use App\Modules\Administrator\Barbershop\Http\Requests\Category\CategoryRequest;
use App\Modules\Administrator\Barbershop\Http\Resources\Category\CategoryDataTableItemResource;
use App\Modules\Administrator\Barbershop\Http\Resources\Category\CategorySelectItemResource;

class CategoryController
{
    public function __construct(private CategoryService $categoryService) {}

    public function dataTable(Request $request)
    {
        $items = $this->categoryService->dataTable($request);
        $items['data'] = CategoryDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(CategoryRequest $request)
    {
        $this->categoryService->save($request->validated());
        return ApiResponse::success(null, 'Categoría guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->categoryService->delete($id);
        return ApiResponse::success(null, 'Categoría eliminada correctamente');
    }

    public function selectItems()
    {
        $items = CategorySelectItemResource::collection($this->categoryService->getActiveCategories());
        return ApiResponse::success($items);
    }
}