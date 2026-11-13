<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'price',
        'address',
        'city',
        'rooms',
        'area',
        'status',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'area'  => 'decimal:2',
        'rooms' => 'integer',
    ];

    /**
     * Emlägiň eýesi
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Emlägiň kategoriýasy
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Emlägiň suratlary
     */
    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * Emlägiň esasy suraty
     */
    public function primaryImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_primary', true);
    }

    /**
     * Emlägiň habarlaşma soraglary
     */
    public function inquiries()
    {
        return $this->hasMany(ContactInquiry::class);
    }

    /**
     * Emlägiň halananlary
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Emlägiň kommentariýalary
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * Diňe aktif emläkleri süzmek üçin scope
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
