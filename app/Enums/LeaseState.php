<?php

namespace App\Enums;

enum LeaseState: string
{
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case TERMINATED = 'terminated';
}
