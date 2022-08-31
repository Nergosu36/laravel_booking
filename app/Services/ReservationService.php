<?php

namespace App\Services;

use App\Http\Resources\ReservationResource;
use App\Http\Resources\VacancyResource;
use App\Models\Reservation;
use App\Models\Vacancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class ReservationService
{
    protected ?Collection $vacancies = null;
    protected array $data = [];
    protected array $orderDetails = [];

    public function checkAvailableDaysInGivenPeriod(array $reservationData): array
    {
        $start = $this->data['start'] = new Carbon($reservationData['start_date']);
        $end = $this->data['end'] = new Carbon($reservationData['end_date']);
        $currentDate = clone $start;
        $this->vacancies = new Collection();
        while ($currentDate <= $end) {
            $vacancy = Vacancy::where('date', '=', $currentDate)->where('slots', '>=', $reservationData['number_of_guests'])->first();
            if (!$vacancy) {
                $this->data['daysUnavailable'][] = $currentDate->format('Y-m-d');
            } else {
                $this->data['daysAvailable'][] = $currentDate->format('Y-m-d');
                $this->vacancies->push($vacancy);
            }
            $currentDate->addDay(1);
        }
        return $this->data;
    }

    public function getOrderDetails(array $orderData): array
    {
        $this->orderDetails['days_reserved'] = $this->data['daysAvailable'];
        $this->orderDetails['price'] = $this->vacancies->sum('price');
        $this->orderDetails['number_of_guests'] = $orderData['number_of_guests'];

        return $this->orderDetails;
    }

    public function makeReservation(): array
    {
        if ($this->vacancies->isEmpty()) {
            return [
                'status' => 'Reservation failed.',
                'info' => 'No vacancies found.'
            ];
        }

        $attributes = [
            'number_of_guests' => $this->orderDetails['number_of_guests'],
            'start_date' => $this->data['start'],
            'end_date' => $this->data['end'],
        ];

        $reservation = new Reservation($attributes);
        $reservationCreated = $reservation->save();

        if ($reservationCreated) {
            foreach ($this->vacancies as $vacancy) {
                $vacancy->slots -= $reservation['number_of_guests'];
                $vacancy->save();
            }
        }

        return [
            'status' => 'Reservation succeed.',
            'data' => new ReservationResource($reservation),
        ];
    }

    public function removeReservation(Reservation $reservation): array
    {
        $start = new Carbon($reservation['start_date']);
        $end = new Carbon($reservation['end_date']);
        $currentDate = $start;
        $this->vacancies = new Collection();
        while ($currentDate <= $end) {
            $vacancy = Vacancy::where('date', '=', $currentDate)->first();
            $this->vacancies->push($vacancy);
            $currentDate->addDay(1);
        }

        if ($this->vacancies->isEmpty()) {
            return [
                'status' => 'Reservation remove failed.',
                'info' => 'No vacancies found.'
            ];
        }

        $reseravtionRemoved = $reservation->delete();

        if ($reseravtionRemoved) {
            foreach ($this->vacancies as $vacancy) {
                $vacancy->slots += $reservation['number_of_guests'];
                $vacancy->save();
            }
        }

        return [
            'status' => 'Reservation removed successfuly.',
            'data' => new ReservationResource($reservation),
        ];
    }
}
