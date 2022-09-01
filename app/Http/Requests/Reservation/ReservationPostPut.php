<?php

namespace App\Http\Requests\Reservation;

use App\Http\Requests\RequestAbstract;

class ReservationPostPut extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'start_date' => 'Date|required|before:end_date',
            'end_date' => 'Date|required',
            'number_of_guests' => 'required',
        ];
    }
}
