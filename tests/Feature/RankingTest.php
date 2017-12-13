<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    public function testGetTop100Miners()
    {
        $user = factory(User::class)->create();
        $top  = factory(User::class)->create([
            'balance' => 75,
        ]);
        $bottom = factory(User::class)->create([
            'balance' => 25,
        ]);
        factory(User::class, 98)->create([
            'balance' => 50,
        ]);
        factory(User::class, 5)->create([
            'balance' => 0,
        ]);

        $response
            = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/ranking/top-100-miners');

        $response->assertStatus(200);

        $json = $response->json();

        $this->assertCount(100, $json['miners']);
        $this->assertEquals($top->nickname, $json['miners'][0]['nickname']);
        $this->assertEquals($top->balance, $json['miners'][0]['balance']);
        $this->assertEquals($bottom->nickname, $json['miners'][99]['nickname']);
        $this->assertEquals($bottom->balance, $json['miners'][99]['balance']);
    }
}
