<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Services;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Toolkito\Larasap\SendTo;

class Twitter implements SocialInterface
{

    use SocialAuthServiceTrait;


    const PROVIDER = 'twitter';


    /**
     * Create New User || Get if Exists
     * [TWITTER]
     *
     * @return $this
     */
    public function createOrGet()
    {
        $this->user = \App\User::updateOrCreate(
            ['provider_id' => $this->data->id, 'provider' => self::PROVIDER],
            [
                'role_id'  => \App\Role::where('name', \App\User::USER_ROLE)->value('id'),
                'provider_token' => $this->data->token,
                'name'      => $this->data->name,
                'email'     => optional($this->data)->email,
                'avatar'    => $this->data->avatar_original,
                'settings'  => new Collection($this->data)
            ]
        );

        abort_if($this->user == null, 400, 'something error');

        return $this;
    }



    public static function publish(User $user, $post, $type = null)
    {
//        $accessToken = $user->provider_token;
//        $settings = json_decode($user->settings, true);
//        $secretAccessToken = null;
//        if (isset($settings['tokenSecret']) && $settings['tokenSecret'] != null) {
//            $secretAccessToken = $settings['tokenSecret'];
//        }
//        SendTo::Twitter($post, [], [], $accessToken, $secretAccessToken);
    }

}