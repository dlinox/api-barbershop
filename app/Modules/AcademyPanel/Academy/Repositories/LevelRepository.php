<?php

namespace App\Modules\AcademyPanel\Academy\Repositories;

use App\Models\Academy\Level;

class LevelRepository
{
    public function dataTable($request)
    {
        $query = Level::query();
        if (empty($request->sortBy)) { $query->orderBy('id', 'desc'); }
        return $query->dataTable($request);
    }

    public function createOrUpdate(array $data) { return Level::updateOrCreate(['id' => $data['id']], $data); }

    public function delete(int $id)
    {
        $level = Level::findOrFail($id);
        if ($level->groups()->exists()) throw new \Exception('No se puede eliminar el nivel porque tiene grupos relacionados');
        $level->delete();
        return $level;
    }

    public function getActiveLevels() { return Level::where('is_active', true)->get(); }
}