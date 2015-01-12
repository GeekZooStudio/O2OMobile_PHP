<?php

use \Carbon\Carbon;

class PermissionGroup extends Eloquent {
    protected $table      = 'permission_group';
    public  $timestamps   = true;
    protected $softDelete = true;
    protected $guarded    = array();
    
}