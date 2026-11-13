<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'message',
    ];

    /**
     * Soragyň degişli emlägi
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
