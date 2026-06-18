<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentAttachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'incident_id',
        'uploaded_by',
        'file_url',
        'file_name',
        'file_type',
        'file_size',
    ];

    public function incident()
    {
        return $this->belongsTo(Incident::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}