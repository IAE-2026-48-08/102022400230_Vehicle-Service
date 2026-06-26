<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleDispatch extends Model
{
    protected $fillable = [
        'vehicle_id',
        'trip_reference',
        'requester_name',
        'destination',
        'start_date',
        'end_date',
        'dispatch_status',
        'approved_by_sso_user_id',
        'approved_role',
        'legacy_receipt_number',
        'legacy_xml_request',
        'legacy_xml_response',
        'published_event_payload',
        'published_event_status',
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'published_event_payload' => 'array',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(SsoUser::class, 'approved_by_sso_user_id');
    }
}
