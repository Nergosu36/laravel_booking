<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationDelete;
use App\Http\Requests\Reservation\ReservationIndex;
use App\Http\Requests\Reservation\ReservationPatch;
use App\Http\Requests\Reservation\ReservationPostPut;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Services\ReservationServic;
use App\Services\ReservationService;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * The reservation service implementation.
     *
     * @var ReservationService
     */
    protected $reservationService;

    /**
     *
     * @param  ReservationService  $reservationService
     * @return void
     */
    public function __construct(ReservationService $reservationService)
    {
        $this->reservationService = $reservationService;
    }

    /**
     * Get list of all reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ReservationIndex $request, Reservation $reservation = null): JsonResponse
    {
        if ($reservation) {
            return response()->json([
                'data' => new ReservationResource($reservation),
            ], 200);
        }

        $reservations = ReservationResource::collection(Reservation::all());

        if ($reservations->isEmpty()) {
            return response()->json([
                'message' => __('apiMessages.reservation.not_found'),
            ], 404);
        }

        return response()->json([
            'data' => $reservations,
        ], 200);
    }

    /**
     * Insert a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(ReservationPostPut $request): JsonResponse
    {
        $validatedData = $request->validated();
        $availability = $this->reservationService->checkAvailableDaysInGivenPeriod($validatedData);
        if (isset($availability['daysUnavailable'])) {
            return response()->json([
                'message' => 'Not all days for given period are available.',
                'unavailable_days' => $availability['daysUnavailable'],
            ], 404);
        }
        $this->reservationService->getOrderDetails($validatedData);
        $summary = $this->reservationService->makeReservation();

        return response()->json([
            'summary' => $summary,
        ], 200);
    }

    /**
     * Delete existing reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(ReservationDelete $request, Reservation $reservation): JsonResponse
    {
        $summary = $this->reservationService->removeReservation($reservation);

        return response()->json([
            'message' => __('apiMessages.reservation.deleted'),
            'summary' => $summary,
        ], 202);
    }
}
