<?php

namespace App\Modules\Administrator\Inventory\Http\Resources\Sale;

use Illuminate\Http\Resources\Json\JsonResource;

class SaleProductResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this['id'],
            'productId'    => $this['product_id'],
            'productName'  => $this['product_name'],
            'presentation' => $this['presentation_name'],
            'sku'          => $this['sku'],
            'salePrice'    => (float) $this['sale_price'],
            'categoryId'   => $this['category_id'],
            'stock'        => (int) ($this['current_stock'] ?? 0),
        ];
    }
}
