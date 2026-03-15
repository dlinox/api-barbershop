<?php

namespace App\Modules\Administrator\Setting\Repositories;

use App\Models\Core\DocumentType;

class DocumentTypeRepository
{
    public function dataTable($request)
    {
        return DocumentType::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        return DocumentType::updateOrCreate(['code' => $data['code']], $data);
    }

    public function delete(string $code)
    {
        $documentType = DocumentType::findOrFail($code);
        $documentType->delete();
        return $documentType;
    }

    public function getActiveDocumentTypes()
    {
        return DocumentType::all();
    }
}
