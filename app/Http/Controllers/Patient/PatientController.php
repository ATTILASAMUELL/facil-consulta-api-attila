<?php

namespace App\Http\Controllers\Patient;

use App\Services\Patient\PatientService;
use App\Traits\ApiResponse;
use App\Http\Resources\Patient\PatientResource;
use App\Http\Requests\Patient\UpdatePatientRequest;
use App\Http\Requests\Patient\CreatePatientRequest;
use App\DTOs\Patient\CreatePatientDTO;
use App\DTOs\Patient\UpdatePatientDTO;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

/**
 * @group Patient
 *
 * API endpoints for managing patients, including creating, updating, and listing patients.
 */
class PatientController extends Controller
{
    use ApiResponse;

    protected PatientService $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
    }

    /**
     * Create a new patient.
     *
     * This endpoint allows you to create a new patient by providing the patient's details.
     *
     * @bodyParam name string required The name of the patient. Example: John Doe
     * @bodyParam cpf string required The CPF of the patient. Example: 123.456.789-00
     * @bodyParam cellphone string required The cellphone of the patient. Example: 555-1234-5678
     *
     * @response 201 {
     *   "message": "Patient created successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "cpf": "123.456.789-00",
     *     "cellphone": "555-1234-5678"
     *   }
     * }
     * @response 400 {
     *   "error": "Failed to create patient. Please try again later."
     * }
     */
    public function store(CreatePatientRequest $request): JsonResponse
    {
        try {
            $dto = CreatePatientDTO::fromRequest($request->validated());
            $patient = $this->patientService->createPatient($dto);
            return $this->successResponse(new PatientResource($patient), 'Patient created successfully', 201);
        } catch (\Exception $e) {
            Log::error('Error creating patient: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to create patient. Please try again later.', 400);
        }
    }

    /**
     * Update an existing patient.
     *
     * This endpoint allows you to update the details of an existing patient.
     *
     * @bodyParam name string optional The new name of the patient. Example: John Doe
     * @bodyParam cpf string optional The new CPF of the patient. Example: 123.456.789-00
     * @bodyParam cellphone string optional The new cellphone of the patient. Example: 555-1234-5678
     *
     * @response 200 {
     *   "message": "Patient updated successfully",
     *   "data": {
     *     "id": 1,
     *     "name": "John Doe",
     *     "cpf": "123.456.789-00",
     *     "cellphone": "555-1234-5678"
     *   }
     * }
     * @response 400 {
     *   "error": "Failed to update patient. Please try again later."
     * }
     */
    public function update(UpdatePatientRequest $request, $id): JsonResponse
    {
        try {
            $dto = UpdatePatientDTO::fromRequest($request->validated());
            $patient = $this->patientService->updatePatient($id, $dto);
            return $this->successResponse(new PatientResource($patient), 'Patient updated successfully');
        } catch (\Exception $e) {
            Log::error('Error updating patient: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to update patient. Please try again later.', 400);
        }
    }

    /**
     * List patients by doctor.
     *
     * This endpoint allows you to retrieve a list of patients for a specific doctor.
     *
     * @queryParam only_scheduled boolean optional Filter only scheduled patients. Example: true
     * @queryParam name string optional Filter patients by name. Example: John
     * @urlParam doctor_id int required The ID of the doctor to filter patients by. Example: 1
     *
     * @response 200 {
     *   "message": "Patients retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "cellphone": "555-1234-5678"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Jane Smith",
     *       "cellphone": "555-9876-5432"
     *     }
     *   ]
     * }
     * @response 400 {
     *   "error": "Failed to retrieve patients. Please try again later."
     * }
     */
    public function listPatients($doctor_id, Request $request): JsonResponse
    {
        try {
            $only_scheduled = $request->get('only_scheduled', false);
            $name = $request->get('name', null);
            $patients = $this->patientService->getPatientsByDoctor($doctor_id, $only_scheduled, $name);
            return $this->successResponse(PatientResource::collection($patients), 'Patients retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error listing patients for doctor ' . $doctor_id . ': ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse('Failed to retrieve patients. Please try again later.', 400);
        }
    }

    /**
     * List patients by doctor with additional filters.
     *
     * This endpoint allows you to retrieve a list of patients for a specific doctor with additional filters like scheduled-only and city name.
     *
     * @queryParam scheduled-only boolean optional Filter only scheduled patients. Example: true
     * @queryParam nome string optional Filter patients by city name. Example: New York
     * @urlParam doctor_id int required The ID of the doctor to filter patients by. Example: 1
     *
     * @response 200 {
     *   "message": "Patients retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "John Doe",
     *       "cellphone": "555-1234-5678"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Jane Smith",
     *       "cellphone": "555-9876-5432"
     *     }
     *   ]
     * }
     * @response 400 {
     *   "error": "Failed to retrieve patients. Please try again later."
     * }
     */
    public function listPatientsByDoctor(Request $request, $doctor_id): JsonResponse
    {
        try {
            $doctor_id = (int) $doctor_id; // Ensuring doctor_id is an integer
            $onlyScheduled = (bool) $request->query('scheduled-only', false); // Adjusted parameter
            $cityName = $request->query('nome', '');
            $patients = $this->patientService->getPatientsByDoctor($doctor_id, $onlyScheduled, $cityName);

            return $this->successResponse($patients, 'Patients retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error listing patients for doctor ' . $doctor_id . ': ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'exception' => $e,
            ]);
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
