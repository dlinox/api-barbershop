<?php

namespace App\Modules\Administrator\Setting\Services;

use App\Modules\Administrator\Setting\Repositories\DocumentTypeRepository;
use Illuminate\Http\Request;

class DocumentTypeService
{
    public function __construct(
        private DocumentTypeRepository $documentTypeRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->documentTypeRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->documentTypeRepository->createOrUpdate($data);
    }

    public function delete(string $code)
    {
        return $this->documentTypeRepository->delete($code);
    }

    public function getActiveDocumentTypes()
    {
        return $this->documentTypeRepository->getActiveDocumentTypes();
    }
}
