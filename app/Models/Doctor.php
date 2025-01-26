<?php

namespace App\Models;

use App\Traits\SanitizeData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Doctor extends Model
{
    use HasFactory, SoftDeletes, SanitizeData;

    protected $fillable = [
        'name',
        'specialty',
        'city_id',
    ];

    public static function boot()
    {
        parent::boot();

        static::bootSanitizeData();
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'doctor_id');
    }
}
