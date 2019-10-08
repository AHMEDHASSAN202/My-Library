<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Services;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Toolkito\Larasap\SendTo;


class Telegram
{
    const PROVIDER = 'telegram';

    /**
     * Create New User || Get if Exists
     * [FACEBOOK]
     *
     * @return $this
     */
    public static function createOrGet(array $data)
    {
        $user = User::updateOrCreate(
            ['provider_id'   => self::set($data['id']) , 'provider' => self::PROVIDER],
            [
                'name'  => self::set($data['first_name']) . ' ' . self::set($data['last_name']),
                'avatar'    => self::set($data['photo_url']),
                'settings'  => collect($data)
            ]
        );

        return $user;
    }


    private static function set($var)
    {
        return (isset($var)) ? $var : null;
    }

    public static function publish(User $user, $post, $type = null)
    {
//        $userSettings = json_decode($user->settings, true);
//        $userId = null;
//        if (isset($userSettings['id']) AND $userSettings['id'] != null) {
//            $userId = $userSettings['id'];
//        }
//
//        SendTo::Telegram($post, null, null, $userId);
    }


}