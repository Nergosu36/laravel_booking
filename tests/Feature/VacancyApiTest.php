<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VacancyApiTest extends TestCase
{

    /**
     * Register test.
     *
     */
    public function test_register()
    {
        $data = [
            'email' => 'test.phpunit@gmail.com',
            'password' => 'phpunittest',
            'name' => 'phpunit test account',
        ];
        $response = $this->post('api/register', $data);
        $data = json_decode($response->getContent(), true);

        $response->assertStatus(200);
    }

    /**
     * Login test.
     *
     * @return string
     */
    public function test_login()
    {
        $data = [
            'email' => 'test.phpunit@gmail.com',
            'password' => 'phpunittest',
        ];
        $response = $this->post('api/login', $data);
        $data = json_decode($response->getContent(), true);
        $token = $data['token'];

        $response->assertStatus(200);

        return $token;
    }

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
            'slots' => rand(1, 10),
        ];
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->post('api/vacancies', $data);
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
            'slots' => rand(1, 10),
        ];
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->put('api/vacancies/' . $testingRecordId, $data);

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
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->patch('api/vacancies/' . $testingRecordId, $data);

        $response->assertStatus(200);
    }

    /**
     * GET test.
     *
     */
    public function test_get()
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->get('api/vacancies');

        $response->assertStatus(200);
    }

    /**
     * DELETE test with wrong ID.
     *
     */
    public function test_delete_wrongId()
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->delete('api/vacancies/' . 0);

        $response->assertStatus(404);
    }

    /**
     * DELETE test with correct ID.
     *
     * @depends test_post
     */
    public function test_delete_correctId($testingRecordId)
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->delete('api/vacancies/' . $testingRecordId);

        $response->assertStatus(200);
    }

    /**
     * GET test for if record does not exists after delete.
     *
     * @depends test_post
     */
    public function test_get_afterDelete($testingRecordId)
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->get('api/vacancies/' . $testingRecordId);

        $response->assertStatus(404);
    }
}
