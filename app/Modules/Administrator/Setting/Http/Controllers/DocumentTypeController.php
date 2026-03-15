<?php

namespace App\Modules\Administrator\Setting\Http\Controllers;

use Illuminate\Http\Request;
use App\Common\Http\Responses\ApiResponse;
use App\Modules\Administrator\Setting\Services\DocumentTypeService;
use App\Modules\Administrator\Setting\Http\Requests\DocumentType\DocumentTypeRequest;
use App\Modules\Administrator\Setting\Http\Resources\DocumentType\DocumentTypeDataTableItemResource;
use App\Modules\Administrator\Setting\Http\Resources\DocumentType\DocumentTypeSelectItemResource;

class DocumentTypeController
{
    public function __construct(
        private DocumentTypeService $documentTypeService
    ) {}

    public function dataTable(Request $request)
    {
        $items = $this->documentTypeService->dataTable($request);
        $items['data'] = DocumentTypeDataTableItemResource::collection($items['data']);
        return ApiResponse::success($items);
    }

    public function save(DocumentTypeRequest $request)
    {
        $data = $request->validated();
        $this->documentTypeService->save($data);
        return ApiResponse::success($data, 'Tipo de documento guardado correctamente');
    }

    public function delete(string $code)
    {
        $this->documentTypeService->delete($code);
        return ApiResponse::success(null, 'Tipo de documento eliminado correctamente');
    }

    public function selectItems()
    {
        $items = $this->documentTypeService->getActiveDocumentTypes();
        $items = DocumentTypeSelectItemResource::collection($items);
        return ApiResponse::success($items);
    }
}
