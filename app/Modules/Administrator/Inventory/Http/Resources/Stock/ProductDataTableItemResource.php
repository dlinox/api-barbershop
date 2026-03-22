<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDataTableItemResource extends JsonResource
{
    public function toArray($request)
    {

        $presentations = $this->presentations->map(function ($presentation) {
            return [
                'id' => $presentation->id,
                'productId' => $presentation->product_id,
                'sku' => $presentation->sku,
                'name' => $presentation->name,
                'unitType' => $presentation->unit_type,
                'quantity' => (int)$presentation->quantity,
                // 'barcode' => $presentation->barcode,
                'minStock' => $presentation->min_stock,
                'maxStock' => $presentation->max_stock,
                'costPrice' => (float) $presentation->cost_price,
                'salePrice' => (float) $presentation->sale_price,
                'isDefault' => (bool) $presentation->is_default,
                'isActive' => (bool) $presentation->is_active,
            ];
        });

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'category' => [
                'id' => $this->category_id,
                'name' => $this->category?->name,
            ],
            'brand' => [
                'id' => $this->brand_id,
                'name' => $this->brand?->name,
            ],
            'isForSale' => $this->is_for_sale,
            'isForInternal' => $this->is_for_internal,
            'imageUrl' => $this->image_url,
            'presentations' => $presentations,
            'isActive' => $this->is_active,
        ];
    }
}
