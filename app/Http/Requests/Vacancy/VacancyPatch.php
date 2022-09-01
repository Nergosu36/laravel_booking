<?php

namespace App\Http\Requests\Vacancy;

use App\Http\Requests\RequestAbstract;

class VacancyPatch extends RequestAbstract
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
            'date' => 'date',
            'price' => 'numeric|min:0',
            'slots' => 'integer|min:1',
        ];
    }
}
