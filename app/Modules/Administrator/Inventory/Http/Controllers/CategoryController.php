<?php

namespace App\Modules\Administrator\Inventory\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Inventory\Services\CategoryService;
use App\Modules\Administrator\Inventory\Http\Requests\Category\CategoryRequest;
use App\Modules\Administrator\Inventory\Http\Resources\Category\CategoryDataTableItemResource;
use App\Modules\Administrator\Inventory\Http\Resources\Category\CategorySelectItemResource;

class CategoryController
{
    public function __construct(
        private CategoryService $categoryService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->categoryService->dataTable($request);
        $items['data'] = CategoryDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(CategoryRequest $request)
    {
        $data = $request->validated();
        $this->categoryService->save($data);
        return ApiResponse::success($data, 'Categoría guardada correctamente');
    }

    public function delete(int $id)
    {
        $this->categoryService->delete($id);
        return ApiResponse::success(null, 'Categoría eliminada correctamente');
    }

    public function selectItems(Request $request)
    {
        $type = $request->query('type');
        $items = $this->categoryService->getActiveCategories($type);
        $items = CategorySelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
