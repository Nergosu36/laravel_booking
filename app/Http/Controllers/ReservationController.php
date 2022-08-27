<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reservation\ReservationDelete;
use App\Http\Requests\Reservation\ReservationIndex;
use App\Http\Requests\Reservation\ReservationPatch;
use App\Http\Requests\Reservation\ReservationPostPut;
use Illuminate\Http\JsonResponse;

class ReservationController extends Controller
{
    /**
     * Get list of all reservations.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ReservationIndex $request): JsonResponse
    {

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
