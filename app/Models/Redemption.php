<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'provider',
        'account_number',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
