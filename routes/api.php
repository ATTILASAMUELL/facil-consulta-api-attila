<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\City\CityController;
use App\Http\Controllers\Doctor\DoctorController;
use App\Http\Controllers\Patient\PatientController;
use App\Http\Controllers\Consultation\ConsultationController;

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('profile', [AuthController::class, 'profile']);

    Route::post('doctors/consultations', [ConsultationController::class, 'store']);
    Route::post('consultations', [ConsultationController::class, 'store']);
    Route::put('consultations/{consultation}', [ConsultationController::class, 'update']);

    Route::post('patients', [PatientController::class, 'store']);
    Route::put('patients/{patient}', [PatientController::class, 'update']);

    Route::post('patients/{patient_id}', [PatientController::class, 'update']);
    Route::get('doctors/{doctor_id}/patients', [PatientController::class, 'listPatientsByDoctor']);
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('cities', [CityController::class, 'index']);
Route::get('cities/{city_id}/doctors', [DoctorController::class, 'index']);
Route::get('cities/{city_id}/doctors/specialty', [DoctorController::class, 'listBySpecialty']);
Route::get('doctors', [DoctorController::class, 'index']);

