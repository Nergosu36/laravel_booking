<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vacancy\VacancyDelete;
use App\Http\Requests\Vacancy\VacancyIndex;
use App\Http\Requests\Vacancy\VacancyPatch;
use App\Http\Requests\Vacancy\VacancyPostPut;
use App\Http\Resources\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;

class VacancyController extends Controller
{
    /**
     * Get list of all vacancies.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(VacancyIndex $request, Vacancy $vacancy = null): JsonResponse
    {
        if($vacancy){
            return response()->json([
                'data' => new VacancyResource($vacancy),
            ], 200);
        }

        $vacancies = VacancyResource::collection(Vacancy::all());

        if($vacancies->isEmpty()){
            return response()->json([
                'message' => __('apiMessages.vacancy.not_found'),
            ], 404);
        }

        return response()->json([
            'data' => $vacancies,
        ], 200);
    }

    /**
     * Insert a vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function post(VacancyPostPut $request): JsonResponse
    {
        $validateData = $request->validated();

        $created = new Vacancy($validateData);
        $created->save();

        return response()->json([
            'message' => __('apiMessages.vacancy.created'),
            'model' => $created,
        ], 200);
    }

     /**
     * Update a vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function put(VacancyPostPut $request, Vacancy $vacancy): JsonResponse
    {
        $validateData = $request->validated();

        $vacancy->update($validateData);
        $vacancy->save();
        
        return response()->json([
            'message' => __('apiMessages.vacancy.updated'),
            'model' => $vacancy,
        ], 200);
    }

    /**
     * Edit existing vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function patch(VacancyPatch $request, Vacancy $vacancy): JsonResponse
    {
        $validateData = $request->validated();

        $vacancy->update($validateData);
        $vacancy->save();
        
        return response()->json([
            'message' => __('apiMessages.vacancy.updated'),
            'model' => $vacancy,
        ], 200);
    }

    /**
     * Delete existing vacancy.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(VacancyDelete $request, Vacancy $vacancy): JsonResponse
    {
        $vacancy->delete();

        return response()->json([
            'message' => __('apiMessages.vacancy.deleted'),
        ], 200);
    }
}
