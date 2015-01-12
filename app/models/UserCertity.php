<?php

use \Carbon\Carbon;

class UserCertity extends Eloquent {

    const STATE_APPLY = 0;  //申请
    const STATE_OK = 1;     //通过
    const STATE_REFUSE = 2; //拒绝 

    protected $table    = 'users_certity';
    public  $timestamps = true;
    protected $guarded  = array();

}