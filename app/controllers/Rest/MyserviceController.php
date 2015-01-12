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
use \App;
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

class MyServiceController extends BaseController {

    const ERROR_THIS_SERVICE_NOT_FOR_YOU    = 19; //此服务不属于你

    /**
     * 获取某人服务列表
     *
     * @return json
     */
    public function postList()
    {

        $uid             = Input::get('uid');
        $user_id         = Input::get('user');
        $pageSize        = Input::get('count', 10);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $orderWhere = '`M`.`user_id` = '.$user_id;
        $total = 0;
        //按页码分页
        if (Input::has('by_no')) {
            $page = abs(Input::get('by_no', 1));
            $page > 1 || $page = 1;
            $offset = ' OFFSET '. $pageSize * ($page - 1);
        //按id分页，
        } elseif (Input::has('by_id')) {
            $lastId   = Input::get('by_id');
            if ($lastId > 0) {
                $orderWhere .= ' AND `M`.`id` < ' . $lastId;
            }
        }
 // GROUP BY `M`.`services_id`
        $sql = "SELECT *
                FROM `o2omobile_my_services` AS `M`

                WHERE $orderWhere  AND `M`.deleted_at is NULL
                ORDER BY `M`.`created_at` DESC
                LIMIT $limit
                $offset";

        $myserviceids = DB::select($sql);

        $collection = new Collection;
        foreach ($myserviceids as $myservice) {
            $collection->add(with(new MyService)->fill((array)$myservice));
        }
        $myservices = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $myservice) {
                $myservices[] = $myservice->formatToApi();
            }
        }//endif

        $return = array(
                    'total'  => $total,
                    'count'  => count($myservices) < $pageSize ? count($myservices) : $pageSize,
                    'more'   => $hasMore,
                    'services' => $myservices,
                   );

        return $this->json($return);
    }




    /**
     * 修改某人服务
     *
     * @return json
     */
    public function postModify()
    {

        $user_id         = Input::get('uid');
        $my_service_id   = Input::get('service_id');
        $price           = Input::get('price');


        $myservice = MyService::findOrFail($my_service_id);

        if($myservice->user_id != $user_id){
            return self::error(self::ERROR_THIS_SERVICE_NOT_FOR_YOU, '此服务不属于你');
        }

        DB::table('my_services')
            ->where('id', $my_service_id)
            ->update(array('price' => $price));

        $myservice->price = $price;
        return $this->json(array('service' => $myservice->formatToApi()));

    }


}