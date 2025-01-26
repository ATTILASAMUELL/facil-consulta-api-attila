<?php

namespace App\Http\Controllers\City;

use App\Services\City\CityService;
use App\Traits\ApiResponse;
use App\Http\Resources\City\CityResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * @group City
 *
 * API endpoints for managing cities, including searching and retrieving all cities.
 */
class CityController extends Controller
{
    use ApiResponse;

    protected CityService $cityService;

    public function __construct(CityService $cityService)
    {
        $this->cityService = $cityService;
    }

    /**
     * Get a list of cities.
     *
     * This endpoint allows you to retrieve a list of all cities or search for cities by name.
     *
     * @queryParam name string optional Filter cities by name. Example: New York
     *
     * @response 200 {
     *   "message": "Cities retrieved successfully",
     *   "data": [
     *     {
     *       "id": 1,
     *       "name": "New York"
     *     },
     *     {
     *       "id": 2,
     *       "name": "Los Angeles"
     *     }
     *   ]
     * }
     * @response 400 {
     *   "error": "Failed to retrieve cities. Please try again later."
     * }
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $name = $request->query('name', '');

            $cities = $name
                ? $this->cityService->searchCitiesByName($name)
                : $this->cityService->getAllCities();

            return $this->successResponse(CityResource::collection($cities), 'Cities retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
    }
}
