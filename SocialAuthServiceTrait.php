<?php
/**
 * Created by PhpStorm.
 * User: AHMED HASSAN
 */

namespace App\Services;


use Illuminate\Support\Facades\Auth;
//use Laravel\Socialite\Two\User;

trait SocialAuthServiceTrait {

    /**
     * Data returned From Api
     *
     * @var User|null
     */
    protected $data = null;

    /**
     * User Model
     *
     * @var null
     */
    protected $user = null;


    /**
     * SocialAuthServiceTrait constructor.
     *
     *
     * @param User $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Login With User Model
     *
     * @return $this
     */
    public function login()
    {
        if ($this->user) {
            Auth::login($this->user);
        }

        return $this;
    }


}