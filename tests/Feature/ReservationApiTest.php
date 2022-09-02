<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationApiTest extends TestCase
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
        $day = rand(-7, 7);
        $testingRecordId = 0;
        $data = [
            'start_date' => Carbon::now()->addDays($day)->toDateString(),
            'end_date' => Carbon::now()->addDays($day + 2)->toDateString(),
            'number_of_guests' => rand(1, 10),
        ];
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->post('api/reservations', $data);

        $response->assertStatus(404);

        return $testingRecordId;
    }

    /**
     * GET test.
     *
     */
    public function test_get()
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->get('api/reservations');

        $response->assertStatus(200);
    }

    /**
     * DELETE test with wrong ID.
     *
     */
    public function test_delete_wrongId()
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->delete('api/reservations/' . 0);

        $response->assertStatus(404);
    }

    /**
     * GET test for if record does not exists.
     *
     * @depends test_post
     */
    public function test_get_afterDelete($testingRecordId)
    {
        $user = User::where('name', '=', 'phpunit test account')->get()->first();
        $response = $this->actingAs($user, 'api')->get('api/reservations/' . $testingRecordId);

        $response->assertStatus(404);
    }
}
