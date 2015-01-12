<?php

//
//       _/_/_/                      _/            _/_/_/_/_/
//    _/          _/_/      _/_/    _/  _/              _/      _/_/      _/_/
//   _/  _/_/  _/_/_/_/  _/_/_/_/  _/_/              _/      _/    _/  _/    _/
//  _/    _/  _/        _/        _/  _/          _/        _/    _/  _/    _/
//   _/_/_/    _/_/_/    _/_/_/  _/    _/      _/_/_/_/_/    _/_/      _/_/
//
//
//  Copyright (c) 2015-2016, Geek Zoo Studio
//  http://www.geek-zoo.com
//
//
//  Permission is hereby granted, free of charge, to any person obtaining a
//  copy of this software and associated documentation files (the "Software"),
//  to deal in the Software without restriction, including without limitation
//  the rights to use, copy, modify, merge, publish, distribute, sublicense,
//  and/or sell copies of the Software, and to permit persons to whom the
//  Software is furnished to do so, subject to the following conditions:
//
//  The above copyright notice and this permission notice shall be included in
//  all copies or substantial portions of the Software.
//
//  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
//  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
//  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
//  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
//  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
//  FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
//  IN THE SOFTWARE.
//

namespace Rest;

use \DB;
use \User;
use \Auth;
use \Input;
use \Tender;
use \Config;
use \Client;
use \Service;
use \MyService;
use \ClientSession;
use \Carbon\Carbon;
use \Illuminate\Database\Eloquent\Collection as Collection;

class ServicecategoryController extends BaseController {


    /**
     * 获取服务分类列表
     *
     * @return json
     */
    public function postList()
    {

        $uid                    = Input::get('uid');
        $service_category_id    = Input::get('service_category_id', 1); //父类目id

        $sql = "SELECT *
                FROM `o2omobile_services` AS `S`
                WHERE `S`.parent_id = $service_category_id";

        $serviceids = DB::select($sql);

        $collection = new Collection;

        foreach ($serviceids as $service) {
            $collection->add(with(new Service)->fill((array)$service));
        }

        $services = array();
        if (!empty($collection)) {
            //格式化
            foreach ($collection as $service) {
                $services[] = $service->formatToApi();
            }
        }//endif

        return $this->json(array('servicecategorys' =>$services));
    }


}