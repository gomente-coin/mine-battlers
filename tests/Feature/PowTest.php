<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PowTest extends TestCase
{
    use RefreshDatabase;

    public function testChallenge()
    {
        $target = Config::get('pow.target');
        $user   = factory(User::class)->create();

        $response
            = $this
            ->actingAs($user, 'api')
            ->json('GET', '/api/pow/challenge');

        $response
            ->assertStatus(200)
            ->assertJson([
                'hash'   => $user->pow_hash,
                'target' => $target,
            ]);
    }

    public function testGoodResponse()
    {
        $target = Config::get('pow.target');
        $user   = factory(User::class)->create([
            'pow_hash' => '00000100098a7cade3f6fce13978bcd7e41eb33c7672b10bdf2401e021b84dec',
        ]);

        $response
            = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/pow/response', [
                'nounce' => 'iHpS1Y67Dnj5vj1y',
            ]);

        $response
            ->assertStatus(200)
            ->assertJson([
                'hash'   => '00000355b1f6c934ff6f8d1e6f741e99adcae85a6d4b8328bdbb9fdc2753087c',
                'target' => $target,
            ]);

        $user = $user->fresh();

        $this->assertEquals(
            '00000355b1f6c934ff6f8d1e6f741e99adcae85a6d4b8328bdbb9fdc2753087c',
            $user->pow_hash
        );
        $this->assertEquals(1, $user->balance);
    }

    public function testBadResponse()
    {
        $user = factory(User::class)->create([
            'pow_hash' => '00000100098a7cade3f6fce13978bcd7e41eb33c7672b10bdf2401e021b84dec',
        ]);

        $response
            = $this
            ->actingAs($user, 'api')
            ->json('POST', '/api/pow/response', [
                'nounce' => 'AAAAAAAAAAAAAAAA',
            ]);

        $response->assertStatus(422)
            ->assertJson([
                'error' => 'The hash is greater than the target.',
            ]);

        $user = $user->fresh();

        $this->assertEquals(
            '00000100098a7cade3f6fce13978bcd7e41eb33c7672b10bdf2401e021b84dec',
            $user->pow_hash
        );
        $this->assertEquals(0, $user->balance);
    }
}
