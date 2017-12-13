<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class PowController extends Controller
{
    public function getChallenge()
    {
        $user   = Auth::guard('api')->user();
        $target = Config::get('pow.target');

        return [
            'balance' => $user->balance,
            'hash'    => $user->pow_hash,
            'target'  => $target,
        ];
    }

    public function postResponse(Request $request)
    {
        $user   = Auth::guard('api')->user();
        $nounce = $request->input('nounce');
        $hash   = hash('sha256', hash('sha256', $user->pow_hash.$nounce));
        $target = Config::get('pow.target');

        if (strcmp($hash, $target) > 0) {
            return response()->json([
                'error' => 'The hash is greater than the target.',
            ], 422);
        }

        $user->pow_hash = $hash;
        $user->balance++;
        $user->save();

        return [
            'balance' => $user->balance,
            'hash'    => $hash,
            'target'  => $target,
        ];
    }
}
