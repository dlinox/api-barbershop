<?php

namespace App\Modules\Administrator\Inventory\Repositories;

use App\Models\Inventory\ProductPresentation;
use App\Models\Inventory\Sale;
use Illuminate\Http\Request;

class SaleRepository
{

    public function getProductsWithStock(int $infrastructureId)
    {
        return ProductPresentation::select(
            'inventory_product_presentations.id',
            'inventory_product_presentations.product_id',
            'inventory_product_presentations.name',
            'inventory_product_presentations.sku',
            'inventory_product_presentations.sale_price',
            'inventory_product_presentations.quantity',

            'inventory_products.id as product_id',
            'inventory_products.name as product_name',

            'inventory_stocks.current_stock',
        )
            ->join('inventory_stocks', 'inventory_product_presentations.id', '=', 'inventory_stocks.presentation_id')
            ->join ('inventory_products', 'inventory_product_presentations.product_id', '=', 'inventory_products.id')
            ->where('inventory_stocks.infrastructure_id', $infrastructureId)
            ->where('inventory_product_presentations.is_active', true)
            ->where('inventory_stocks.current_stock', '>', 0)
            ->get();
    }

    public function dataTable(Request $request)
    {
        $query = Sale::select(
                'inventory_sales.*',
                'core_persons.name as person_name',
                'core_persons.paternal_surname as person_paternal_surname',
                'core_persons.maternal_surname as person_maternal_surname',
                'core_persons.document_number as person_document_number',
                'auth_users.username as user_username',
            )
            ->join('treasury_cash_sessions', 'treasury_cash_sessions.id', 'inventory_sales.cash_session_id')
            ->join('treasury_cash_registers', 'treasury_cash_registers.id', 'treasury_cash_sessions.cash_register_id')
            ->leftJoin('core_persons', 'core_persons.id', 'inventory_sales.person_id')
            ->leftJoin('auth_users', 'auth_users.id', 'inventory_sales.user_id');

        if (empty($request->sortBy) || !isset($request->sortBy)) {
            $query->orderBy('inventory_sales.id', 'desc');
        }

        return $query->dataTable($request);
    }

    public function getById(int $id)
    {
        return Sale::with(['items.presentation.product'])
            ->findOrFail($id);
    }
}
