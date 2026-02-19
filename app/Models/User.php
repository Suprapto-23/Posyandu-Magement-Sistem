<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',        // <--- INI YANG BENAR (Sesuai database Anda)
        'email',
        'password',
        'role',
        'status',
        'last_login_at',
        'created_by',
        'must_change_password',
        // 'username',  <-- HAPUS INI (Tidak ada di database users Anda)
        // 'nik',       <-- HAPUS INI (NIK ada di tabel profiles, bukan users)
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'must_change_password' => 'boolean',
    ];

    // ==================== RELASI ====================
    
    public function profile()
{
    return $this->hasOne(Profile::class);
}

    public function bidan()
    {
        return $this->hasOne(Bidan::class);
    }

    public function kader()
    {
        return $this->hasOne(Kader::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loginLogs()
    {
        return $this->hasMany(LoginLog::class);
    }

    // ==================== HELPER METHODS ====================
    
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isBidan()
    {
        return $this->role === 'bidan';
    }

    public function isKader()
    {
        return $this->role === 'kader';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }

    // Generate password otomatis
    public static function generatePassword()
    {
        return Str::random(8);
    }

    // Generate password berdasarkan NIK
    public static function generatePasswordFromNIK($nik)
    {
        $last4 = substr($nik, -4);
        $tahunLahir = substr($nik, 10, 4);
        return $last4 . $tahunLahir;
    }

    // Get full name (Helper attribute)
    public function getFullNameAttribute()
    {
        // Jika punya profile, ambil dari profile. Jika tidak, ambil dari name user.
        return $this->profile ? $this->profile->full_name : $this->name;
    }
}