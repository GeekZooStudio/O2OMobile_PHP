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

class ServicetypeController extends BaseController {


    /**
     * 获取服务分类列表
     *
     * @return json
     */
    public function postList()
    {

        $uid             = Input::get('uid');
        $pageSize        = Input::get('count', 10);
        $limit           = $pageSize + 1;
        $offset          = '';
        $hasMore         = 0;
        $orderWhere = 'parent_id=0';
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
                $orderWhere .= ' AND `S`.`id` < ' . $lastId;
            }
        }

        $sql = "SELECT *
                FROM `o2omobile_services` AS `S`
                WHERE $orderWhere
                ORDER BY `S`.`usort` DESC
                LIMIT $limit
                $offset";

        $serviceids = DB::select($sql);

        $collection = new Collection;
        foreach ($serviceids as $service) {
            $collection->add(with(new Service)->fill((array)$service));
        }

        $services = array();
        if (!empty($collection)) {
            if (count($collection) > $pageSize) {
                $hasMore = 1;
                $collection->pop();//弹出最后一条
            }
            //格式化
            foreach ($collection as $service) {
                $services[] = $service->formatToApi();
            }
        }//endif

        $return = array(
                    'total'  => $total,
                    'count'  => count($services) < $pageSize ? count($services) : $pageSize,
                    'more'   => $hasMore,
                    'services' => $services,
                   );

        return $this->json($return);
    }


}