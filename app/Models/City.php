<?php

namespace App\Models;

use App\Traits\SanitizeData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
{
    use HasFactory, SoftDeletes, SanitizeData;

    protected $fillable = [
        'name',
        'state',
    ];

    public static function boot()
    {
        parent::boot();

        static::bootSanitizeData();
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class, 'city_id');
    }
}
