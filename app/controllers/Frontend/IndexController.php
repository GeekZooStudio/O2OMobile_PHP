<?php namespace Frontend;
use \Redirect;

class IndexController extends BaseController {

    public function index() {
        return Redirect::to('http://www.o2omobile.com.cn');
    }

}