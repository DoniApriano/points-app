<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'description', // kolom sebelumnya tidak ada
        'transacted_at',
    ];

    protected $dates = ['transacted_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function point()
    {
        return $this->hasOne(Point::class);
    }
}
