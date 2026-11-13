<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Ulanyjynyň admin rol barlagçy
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Ulanyjynyň API tokenleri
     */
    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }

    /**
     * Ulanyjynyň emläkleri
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Ulanyjynyň halananlary
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Ulanyjynyň kommentariýalary
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Täze API token döretmek
     */
    public function createApiToken(string $name = 'default'): ApiToken
    {
        return $this->apiTokens()->create([
            'token' => bin2hex(random_bytes(40)),
            'name'  => $name,
        ]);
    }
}
