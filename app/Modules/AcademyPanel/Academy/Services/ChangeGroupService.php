<?php

namespace App\Modules\AcademyPanel\Academy\Services;

use Illuminate\Support\Facades\Auth;
use App\Modules\Administrator\Academy\Repositories\Actions\ChangeGroupAction;

class ChangeGroupService
{
    public function __construct(
        private ChangeGroupAction $changeGroupAction,
    ) {}

    public function changeGroup(array $data): array
    {
        $newEnrollment = $this->changeGroupAction->execute(
            originEnrollmentId: $data['enrollment_id'],
            targetGroupId:      $data['target_group_id'],
            reason:             $data['reason'] ?? null,
            userId:             Auth::id(),
        );

        return [
            'newEnrollmentId' => $newEnrollment->id,
            'changeId'        => $newEnrollment->changeId,
        ];
    }
}
