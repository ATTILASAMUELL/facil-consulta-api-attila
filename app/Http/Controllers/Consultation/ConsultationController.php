<?php

namespace App\Http\Controllers\Consultation;

use App\Services\Consultation\ConsultationService;
use App\Traits\ApiResponse;
use App\Http\Resources\Consultation\ConsultationResource;
use App\Http\Requests\Consultation\ConsultationRequest;
use App\DTOs\Consultation\ConsultationDTO;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * @group Consultation
 *
 * API endpoints for managing consultations, including creating and updating consultations.
 */
class ConsultationController extends Controller
{
    use ApiResponse;

    protected ConsultationService $consultationService;

    public function __construct(ConsultationService $consultationService)
    {
        $this->consultationService = $consultationService;
    }

    /**
     * Create a new consultation.
     *
     * This endpoint allows you to create a new consultation by providing the doctor, patient, and consultation date.
     *
     * @bodyParam doctor_id int required The ID of the doctor. Example: 1
     * @bodyParam patient_id int required The ID of the patient. Example: 1
     * @bodyParam date string required The date and time of the consultation. Example: 2025-01-01 10:00:00
     *
     * @response 201 {
     *   "message": "Consultation created successfully",
     *   "data": {
     *     "id": 1,
     *     "doctor_id": 1,
     *     "patient_id": 1,
     *     "date": "2025-01-01 10:00:00"
     *   }
     * }
     * @response 400 {
     *   "error": "Failed to create consultation. Please try again later."
     * }
     */
    public function store(ConsultationRequest $request): JsonResponse
    {
        try {
            $data = ConsultationDTO::fromArray($request->validated());
            $consultation = $this->consultationService->createConsultation($data);
            return $this->successResponse(new ConsultationResource($consultation), 'Consultation created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }

    /**
     * Update an existing consultation.
     *
     * This endpoint allows you to update the details of an existing consultation.
     *
     * @bodyParam doctor_id int optional The ID of the doctor. Example: 1
     * @bodyParam patient_id int optional The ID of the patient. Example: 1
     * @bodyParam date string optional The new date and time of the consultation. Example: 2025-01-01 11:00:00
     *
     * @response 200 {
     *   "message": "Consultation updated successfully",
     *   "data": {
     *     "id": 1,
     *     "doctor_id": 1,
     *     "patient_id": 1,
     *     "date": "2025-01-01 11:00:00"
     *   }
     * }
     * @response 400 {
     *   "error": "Failed to update consultation. Please try again later."
     * }
     */
    public function update(ConsultationRequest $request, $id): JsonResponse
    {
        try {
            $data = ConsultationDTO::fromArray($request->validated());
            $consultation = $this->consultationService->updateConsultation($id, $data);
            return $this->successResponse(new ConsultationResource($consultation), 'Consultation updated successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
