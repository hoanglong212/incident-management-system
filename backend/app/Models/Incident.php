<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Incident extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'title',
        'description',
        'category_id',
        'priority',
        'status',
        'reporter_id',
        'assigned_to',
        'address',
        'ward',
        'district',
        'city',
        'latitude',
        'longitude',
        'occurred_at',
        'assigned_at',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'occurred_at' => 'datetime',
        'assigned_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(IncidentCategory::class, 'category_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function attachments()
    {
        return $this->hasMany(IncidentAttachment::class);
    }

    public function comments()
    {
        return $this->hasMany(IncidentComment::class);
    }

    public function statusLogs()
    {
        return $this->hasMany(IncidentStatusLog::class);
    }
}