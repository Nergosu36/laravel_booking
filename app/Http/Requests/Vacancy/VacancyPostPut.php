<?php

namespace App\Http\Requests\Vacancy;

use App\Http\Requests\RequestAbstract;

class VacancyPostPut extends RequestAbstract
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'slots' => 'required|integer|min:1',
        ];
    }
}
