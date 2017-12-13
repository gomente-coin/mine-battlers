<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Http\Controllers\Controller;

class RankingController extends Controller
{
    public function getTop100Miners()
    {
        $miners = User::orderBy('balance', 'desc')->limit(100)->get()->map(function ($user) {
            return [
                'nickname' => $user->nickname,
                'balance'  => $user->balance,
            ];
        })->all();

        return [
            'miners' => $miners,
        ];
    }
}
