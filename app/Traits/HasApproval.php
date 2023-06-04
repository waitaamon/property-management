<?php

namespace App\Traits;

use App\Models\Approval;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasApproval
{
    public function approvals():MorphMany
    {
        return $this->morphMany(Approval::class, 'approveable');
    }
}
