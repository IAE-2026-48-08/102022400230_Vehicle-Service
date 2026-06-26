<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SsoUser extends Model
{
    protected $fillable = [
        'sso_subject',
        'name',
        'email',
        'last_jwt_payload',
        'last_login_at',
    ];

    protected $casts = [
        'last_jwt_payload' => 'array',
        'last_login_at' => 'datetime',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(VehicleDispatch::class, 'approved_by_sso_user_id');
    }
}
