<?php

namespace App\Models;

use App\Traits\HasLogs;
use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class Approval extends Model
{
    use HasFactory, HasLogs;

    protected $fillable = ['user_id', 'note', 'status'];

    protected $casts = [
        'status' => ApprovalStatus::class,
    ];

    public function approveable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
