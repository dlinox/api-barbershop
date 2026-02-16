<?php

namespace App\Modules\Administrator\Academy\Services;

use App\Modules\Administrator\Academy\Repositories\LevelRepository;
use Illuminate\Http\Request;

class LevelService
{
    public function __construct(
        private LevelRepository $levelRepository
    ) {}

    public function dataTable(Request $request)
    {
        return $this->levelRepository->dataTable($request);
    }

    public function save(array $data)
    {
        return $this->levelRepository->createOrUpdate($data);
    }

    public function delete(int $id)
    {
        return $this->levelRepository->delete($id);
    }

    public function getActiveLevels()
    {
        return $this->levelRepository->getActiveLevels();
    }
}
