<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VacancyApiTest extends TestCase
{
    /**
     * POST test.
     *
     * @return int
     */
    public function test_post()
    {
        $testingRecordId = 0;
        $data = [
            'date' => Carbon::now()->addDays(rand(-7, 7))->toDateString(),
            'price' => rand(0, 100) + (rand(0, 9) / 10),
            'slots' => rand(0, 10),
        ];
        $response = $this->post('api/vacancies', $data);
        $data = json_decode($response->getContent(), true);
        $testingRecordId = $data['model']['id'];

        $response->assertStatus(200);

        return $testingRecordId;
    }

    /**
     * PUT test (correct data).
     * @depends test_post
     */
    public function test_put($testingRecordId)
    {
        $data = [
            'date' => Carbon::now()->addDays(rand(-7, 7))->toDateString(),
            'price' => rand(0, 100) + (rand(0, 9) / 10),
            'slots' => rand(0, 10),
        ];
        $response = $this->put('api/vacancies/' . $testingRecordId, $data);

        $response->assertStatus(200);
    }

    /**
     * PATCH test with only one variable.
     *
     * @depends test_post
     */
    public function test_patch($testingRecordId)
    {
        $data = [
            'price' => rand(0, 100) + (rand(0, 9) / 10),
        ];
        $response = $this->patch('api/vacancies/' . $testingRecordId, $data);

        $response->assertStatus(200);
    }

    /**
     * GET test.
     *
     */
    public function test_get()
    {
        $response = $this->get('api/vacancies');

        $response->assertStatus(200);
    }

    /**
     * DELETE test with wrong ID.
     *
     */
    public function test_delete_wrongId()
    {
        $response = $this->delete('api/vacancies/' . 0);

        $response->assertStatus(404);
    }

    /**
     * DELETE test with correct ID.
     *
     * @depends test_post
     */
    public function test_delete_correctId($testingRecordId)
    {
        $response = $this->delete('api/vacancies/' . $testingRecordId);

        $response->assertStatus(200);
    }

    /**
     * GET test for if record does not exists after delete.
     *
     * @depends test_post
     */
    public function test_get_afterDelete($testingRecordId)
    {
        $response = $this->get('api/vacancies/' . $testingRecordId);

        $response->assertStatus(404);
    }
}
