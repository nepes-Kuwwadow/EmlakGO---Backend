<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'parent_id',
        'user_id',
        'name',
        'comment',
        'rating',
        'is_approved',
    ];

    protected $casts = [
        'rating'      => 'integer',
        'is_approved' => 'boolean',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Ene teswir
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Jogaplar (replies)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->where('is_approved', true)
                    ->with('user:id,name,role')
                    ->oldest();
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    // Diňe ene teswirler (parent_id = null)
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }
}

