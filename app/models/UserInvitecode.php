<?php

use \Carbon\Carbon;

class UserInvitecode extends Eloquent {

    protected $table    = 'users_invitecode';
    public  $timestamps = true;
    protected $guarded  = array();

}