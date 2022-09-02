<?php

namespace Tests\Feature;

use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReservationServiceTest extends TestCase
{
    /**
     * Create service test.
     *
     * @return ReservationService
     */
    public function test_reservation_service_creation()
    {
        $reservationService = new ReservationService();

        $this->assertInstanceOf(ReservationService::class, $reservationService);

        return $reservationService;
    }

    /**
     * Check available days in given period.
     *
     * @depends test_reservation_service_creation
     */
    public function test_check_available_days($reservationService)
    {
        $day = rand(-7, 7);
        $data = [
            'start_date' => Carbon::now()->addDays($day)->toDateString(),
            'end_date' => Carbon::now()->addDays($day + 2)->toDateString(),
            'number_of_guests' => rand(1, 10),
        ];
        $details = $reservationService->checkAvailableDaysInGivenPeriod($data);

        $this->assertThat($details, $this->logicalOr(
            $this->arrayHasKey('daysAvailable'),
            $this->arrayHasKey('daysUnavailable')
        ));
        return $details;
    }

    /**
     * Get order details when days are available.
     *
     * @depends test_reservation_service_creation
     * @depends test_check_available_days
     */
    public function test_get_order_details($reservationService, $details)
    {
        $summary = null;

        if (!isset($details['daysUnavailable'])) {
            $summary = $reservationService->getOrderDetails($details);
            $this->assertArrayHasKey('price', $summary);
        }else{
            $this->assertNull($summary);
        }
    }
}
