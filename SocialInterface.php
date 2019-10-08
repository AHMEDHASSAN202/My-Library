<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Services;


use App\User;

interface SocialInterface
{
    public function createOrGet();

    public function login();

    public static function publish(User $user, $post, $type = null);
}