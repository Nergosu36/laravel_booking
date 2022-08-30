<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationDelete;
use App\Http\Requests\Reservation\ReservationIndex;
use App\Http\Requests\Reservation\ReservationPatch;
use App\Http\Requests\Reservation\ReservationPostPut;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Get list of all reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ReservationIndex $request, Reservation $reservation = null): JsonResponse
    {
        if($reservation){
            return response()->json([
                'data' => new ReservationResource($reservation),
            ], 200);
        }

        $reservations = ReservationResource::collection(Reservation::all());

        if($reservations->isEmpty()){
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

    }

    /**
     * Update a reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function put(ReservationPostPut $request, Reservation $reservation): JsonResponse
    {

    }

    /**
     * Edit existing reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patch(ReservationPatch $request): JsonReponse
    {
        
    }

    /**
     * Delete existing reservation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(ReservationDelete $request): JsonResponse
    {
        
    }
}
