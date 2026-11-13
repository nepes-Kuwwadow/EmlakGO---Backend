<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'property_id',
    ];

    /**
     * Halaýan ulanyjy
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Halanan emläk
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
