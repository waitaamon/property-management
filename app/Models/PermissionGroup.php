<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PermissionGroup extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_group', 'group_id', 'permission_id');
    }
}
