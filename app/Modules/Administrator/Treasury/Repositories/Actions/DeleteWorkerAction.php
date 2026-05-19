<?php

namespace App\Modules\Administrator\Treasury\Repositories\Actions;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

use App\Common\Exceptions\ApiException;
use App\Models\Profile\Worker;
use App\Models\Core\Person;
use App\Models\Behavior\Profile;

class DeleteWorkerAction
{
    public function execute(int $workerId): void
    {
        try {
            DB::beginTransaction();

            $worker = Worker::find($workerId);
            if (!$worker) throw new ApiException('Trabajador no encontrado');

            if ($worker->payments()->exists()) {
                throw new ApiException('No se puede eliminar el trabajador porque tiene pagos registrados');
            }

            if ($worker->advances()->exists()) {
                throw new ApiException('No se puede eliminar el trabajador porque tiene adelantos registrados');
            }

            $personId = $worker->id;

            $hasOtherProfiles = Profile::where('profileable_id', $personId)->exists();

            $worker->delete();

            if (!$hasOtherProfiles) {
                Person::where('id', $personId)->delete();
            }

            DB::commit();
        } catch (ApiException $e) {
            DB::rollBack();
            throw $e;
        } catch (QueryException $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                throw new ApiException('No se puede eliminar el trabajador porque tiene registros relacionados');
            }
            throw new ApiException('Error al eliminar el trabajador');
        } catch (\Exception $e) {
            DB::rollBack();
            throw new ApiException('Error al eliminar el trabajador');
        }
    }
}
