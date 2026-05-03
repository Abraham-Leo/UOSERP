<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'employee_id', 'title', 'department',
        'phone', 'mobile', 'is_active', 'shop_floor_only', 'default_warehouse'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'shop_floor_only' => 'boolean'
    ];

    public function laborEntries()
    {
        return $this->hasMany(LaborEntry::class);
    }

    public function getInitialsAttribute()
    {
        return strtoupper(implode('', array_map(fn($w) => $w[0], explode(' ', $this->name))));
    }
}