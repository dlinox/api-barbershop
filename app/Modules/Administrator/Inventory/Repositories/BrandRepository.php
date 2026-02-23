<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\Brand;

class BrandRepository
{
    public function dataTable($request)
    {
        return Brand::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return Brand::updateOrCreate(['id' => $data['id']], $data);
    }

    public function delete(int $id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->products()->count() > 0) {
            throw new \Exception('No se puede eliminar la marca porque tiene productos relacionados');
        }

        $brand->delete();
        return $brand;
    }

    public function getActiveBrands()
    {
        return Brand::where('is_active', true)->get();
    }
}
