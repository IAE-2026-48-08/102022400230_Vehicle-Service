<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function ssoUsers(): BelongsToMany
    {
        return $this->belongsToMany(SsoUser::class)->withTimestamps();
    }
}
