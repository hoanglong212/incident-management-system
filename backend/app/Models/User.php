<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable , hasApiTokens, SoftDeletes; 
    protected $fillable = [
     'name',
    'email',
    'password',
    'role_id',
    'phone',
    'avatar_url',
    'status',   
    ];
    public function role()
{
    return $this->belongsTo(Role::class);
}

public function reportedIncidents()
{
    return $this->hasMany(Incident::class, 'reporter_id');
}

public function assignedIncidents()
{
    return $this->hasMany(Incident::class, 'assigned_to');
}

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
