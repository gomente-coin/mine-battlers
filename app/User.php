<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Socialite\One\User as SocialiteUser;

class User extends Authenticatable
{
    const INITIAL_POW_HASH = '0000000000000000000000000000000000000000000000000000000000000000';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'twitter_id',
        'nickname',
        'pow_hash',
        'api_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token',
    ];

    public static function createFromTwitterUser(SocialiteUser $twitterUser)
    {
        return User::create([
            'twitter_id' => $twitterUser->id,
            'nickname'   => $twitterUser->nickname,
            'api_token'  => str_random(60),
            'pow_hash'   => self::INITIAL_POW_HASH,
        ]);
    }

    public function getRememberTokenName()
    {
        return null;
    }
}
