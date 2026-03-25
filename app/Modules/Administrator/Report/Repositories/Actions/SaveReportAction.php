<?php

namespace App\Modules\Administrator\Report\Repositories\Actions;

use App\Models\Reports\Report;

class SaveReportAction
{
    public function execute(string $name, string $reference, string $type, array $data, string $filePath): Report
    {
        $existingVersion = Report::where('reference', $reference)->max('version') ?? 0;

        return Report::create([
            'name'      => $name,
            'reference' => $reference,
            'version'   => $existingVersion + 1,
            'type'      => $type,
            'data'      => $data,
            'file_path' => $filePath,
        ]);
    }
}
