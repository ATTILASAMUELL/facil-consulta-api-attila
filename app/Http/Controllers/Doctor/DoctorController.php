<?php

namespace App\Http\Controllers\Doctor;

use App\Services\Doctor\DoctorService;
use App\Traits\ApiResponse;
use App\Http\Resources\Doctor\DoctorResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @group Doctor
 *
 * API endpoints for managing doctors, including searching by city, specialty, and name.
 */
class DoctorController extends Controller
{
    use ApiResponse;

    protected DoctorService $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }

    /**
     * Get a list of doctors by city.
     *
     * This endpoint allows you to retrieve a list of doctors filtered by city.
     *
     * @queryParam name string optional Filter doctors by name. Example: John Doe
     * @urlParam city_id int required The ID of the city to filter doctors. Example: 1
     *
     * @response 200 {
     *   "message": "Doctors retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Dr. John Doe",
     *       "specialty": "Cardiologist",
     *       "city": {
     *         "id": 1,
     *         "name": "New York"
     *       }
     *     },
     *     {
     *       "id": 2,
     *       "name": "Dr. Jane Smith",
     *       "specialty": "Dermatologist",
     *       "city": {
     *         "id": 1,
     *         "name": "New York"
     *       }
     *     }
     *   ]
     * }
     * @response 400 {
     *   "error": "Failed to retrieve doctors. Please try again later."
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $name = $request->query('name', '');

            if(isset($name)){
                $doctors = $this->doctorService->searchDoctorsByName($name);
            }else{
                $doctors = $this->doctorService->getAllDoctors();
            }

            return $this->successResponse(DoctorResource::collection($doctors), 'Doctors retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Get a list of doctors by city and specialty.
     *
     * This endpoint allows you to retrieve a list of doctors filtered by city and specialty.
     *
     * @queryParam specialty string optional Filter doctors by specialty. Example: Cardiologist
     * @urlParam city_id int required The ID of the city to filter doctors. Example: 1
     *
     * @response 200 {
     *   "message": "Doctors retrieved by specialty successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "Dr. John Doe",
     *       "specialty": "Cardiologist",
     *       "city": {
     *         "id": 1,
     *         "name": "New York"
     *       }
     *     },
     *     {
     *       "id": 2,
     *       "name": "Dr. David Brown",
     *       "specialty": "Cardiologist",
     *       "city": {
     *         "id": 1,
     *         "name": "New York"
     *       }
     *     }
     *   ]
     * }
     * @response 400 {
     *   "error": "Failed to retrieve doctors by specialty. Please try again later."
     * }
     */
    public function listBySpecialty(Request $request, $city_id): JsonResponse
    {
        try {
            $specialty = $request->query('specialty', '');
            $doctors = $this->doctorService->getDoctorsByCityAndSpecialty($city_id, $specialty);

            return $this->successResponse(DoctorResource::collection($doctors), 'Doctors retrieved by specialty successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
