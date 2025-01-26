<?php

namespace App\Models;

use App\Traits\SanitizeData;
use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes, SanitizeData;

    protected $fillable = [
        'name',
        'cpf',
        'cellphone',
    ];

    public static function boot()
    {
        parent::boot();

        static::bootSanitizeData();
    }

    public function consultations()
    {
        return $this->hasMany(Consultation::class, 'patient_id');
    }
}
