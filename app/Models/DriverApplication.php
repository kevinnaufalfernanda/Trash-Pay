<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverApplication extends Model
{
    protected $fillable = [
        'user_id',
        'nik',
        'ktp_photo',
        'sim_photo',
        'stnk_photo',
        'skck_photo',
        'vehicle_plate',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
