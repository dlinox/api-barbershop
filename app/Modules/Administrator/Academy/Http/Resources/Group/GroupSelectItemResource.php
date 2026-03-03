<?php

namespace App\Modules\Administrator\Academy\Http\Resources\Group;

use App\Models\Academy\Enrollment;
use App\Models\Academy\EnrollmentPaymentDetail;
use App\Models\Academy\EnrollmentMaterial;
use App\Models\Academy\Material;
use App\Common\Helpers\DateHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupSelectItemResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'value' => $this->id,
            'title' => $this->level_name . ' - ' . $this->name,
        ];
    }
}
