<?php
/**
 * 此文件是为了重写laravel自带的密码验证方法
 */
use \Illuminate\Auth\EloquentUserProvider;
use \Illuminate\Auth\UserInterface;
use \Illuminate\Auth\GenericUser;
use \Carbon\Carbon;

class O2OMobileUserProvider extends EloquentUserProvider {
    /**
     * Validate a user against the given credentials.
     *
     * @param \Illuminate\Auth\UserInterface $user
     * @param  array  $credentials
     *
     * @return bool
     */
     public function validateCredentials(\Illuminate\Auth\UserInterface $user, array $credentials)
     {
        return $user->password == hash_password($credentials['password']);
     }
}