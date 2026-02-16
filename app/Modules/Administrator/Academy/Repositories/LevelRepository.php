<?php

namespace App\Modules\Administrator\Academy\Repositories;

use App\Models\Academy\Level;

class LevelRepository
{
    public function dataTable($request)
    {
        return Level::dataTable($request);
    }

    public function createOrUpdate(array $data)
    {
        $level = Level::updateOrCreate(['id' => $data['id']], $data);
        return $level;
    }

    public function delete(int $id)
    {
        $level = Level::find($id);

        if ($level->groups()->exists()) {
            throw new \Exception('No se puede eliminar el nivel porque tiene grupos asociados');
        }

        return $level->delete();
    }

    public function getActiveLevels()
    {
        return Level::where('is_active', true)->get();
    }
}
