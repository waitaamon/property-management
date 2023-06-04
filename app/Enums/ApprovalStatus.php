<?php

namespace App\Enums;

enum ApprovalStatus: string
{
    case PENDING_APPROVAL = 'pending approval';
    case APPROVED = 'approved';
    case VOIDED = 'voided';
    case REVERSED = 'reversed';
}
