<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Services;
use App\User;
use Illuminate\Database\Eloquent\Collection;


class Google implements SocialInterface
{
    use SocialAuthServiceTrait;

    const PROVIDER = 'google';

    /**
     * Create New User || Get if Exists
     * [FACEBOOK]
     *
     * @return $this
     */
    public function createOrGet()
    {
        $this->user = \App\User::updateOrCreate(
            ['provider_id' => $this->data->id, 'provider' => self::PROVIDER],
            [
                'role_id' => \App\Role::where('name', \App\User::USER_ROLE)->value('id'),
                'provider_token' => $this->data->token,
                'name' => $this->data->name,
                'email' => optional($this->data)->email,
                'avatar' => $this->data->avatar_original,
                'settings' => new Collection($this->data)
            ]
        );

        abort_if($this->user == null, 400, 'something error');

        return $this;
    }



    public static function publish(User $user, $post, $type = null) {}

}
