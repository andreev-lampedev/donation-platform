<?php

namespace Tests\Unit;

use App\Http\Resources\DonationResource;
use App\Models\Donation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DonationResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function resource_should_pass_when_showing_donation()
    {
        $this->assertEquals(
            ['name', 'email', 'amount', 'message', 'created_at'],
            array_keys((new DonationResource(Donation::factory()->make()))->toArray(request()))
        );
    }
}
