<?php

namespace App\Actions;

use App\Models\Approval;
use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;

class CreateApproval
{
    public static function handle(Model $model, ApprovalStatus $status, string $note): Approval
    {
        $approval = $model->approvals()->create([
            'note' => $note,
            'user_id' => auth()->id(),
            'status' => $status->value,
            'previous_status' => $model->status
        ]);

        $model->update(['status' => $status->value]);

        return $approval;
    }
}
