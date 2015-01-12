@extends('Backend.layout')
@section('page_title')
<h1>订单列表 <!--<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/user/new')}}" class="btn btn-info">新建</a>--></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-1">
                    <input type="text" name="order_id" class="form-control" value="{{Input::get('order_id')}}" placeholder="订单id">
                </div>
                <div class="col-md-2">
                    <input type="text" name="order_sn" class="form-control" value="{{Input::get('order_sn')}}" placeholder="订单编号">
                </div>
                <div class="col-md-3">
                    <input type="text" name="time_range" class="form-control daterange" value="{{Input::get('time_range')}}" placeholder="订单生成时间"/>
                </div>
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".submit-btn" name="order_status">
                        <option value='' >--订单状态--</option>
                        <option value='un' @if(Input::get('order_status') !== '' && Input::get('order_status') == 'un') selected @endif>客户发单</option>
                        <option value='{{Orders::OS_KNOCK_DOWN}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_KNOCK_DOWN) selected @endif>已确认接单</option>
                        <option value='{{Orders::OS_WORK_DONE}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_WORK_DONE) selected @endif>工作完成</option>
                        <option value='{{Orders::OS_PAYED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_PAYED) selected @endif>已付款</option>
                        <option value='{{Orders::OS_PAY_CONFORMED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_PAY_CONFORMED) selected @endif>付款已确认</option>
                        <option value='{{Orders::OS_EMPLOYEE_COMMENTED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_EMPLOYEE_COMMENTED) selected @endif>雇员已评价</option>
                    	<option value='{{Orders::OS_EMPLOYER_COMMENTED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_EMPLOYER_COMMENTED) selected @endif>雇主已评价</option>
                        <option value='{{Orders::OS_FINISHED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_FINISHED) selected @endif>订单结束</option>
                        <option value='{{Orders::OS_CANCELED}}' @if(Input::get('order_status') !== '' && Input::get('order_status') == Orders::OS_CANCELED) selected @endif>订单取消</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="雇主昵称">
                </div>
                <div class="col-md-1">
                    <input type="submit" class="submit-btn btn btn-blue" value="搜索" />
                </div>
            </form>
        </div>
        <hr class="clearfix">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="select-all" data-target=".chkbox">
                    </th>
                    <th>ID</th>
                    <th>订单编号</th>
                    <th>服务类型</th>
                    <th>雇员</th>
                    <th>雇主</th>
                    <th>内容</th>
                    <th>成交价格</th>
                    <th>订单状态</th>
                    <th>下单时间</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($orders))
                <tr>
                    <td colspan="8">无数据</td>
                </tr>
                @else
                @foreach($orders as $order)
                <tr>
                    <td>
                        <input type="checkbox" name="id" class="chkbox" value="{{$order->id}}">
                    </td>
                    <td class="td-id">{{$order->id}}</td>
                    <td>{{$order->order_sn}}</td>
                    <td>{{$order->serviceToHtml()}}</td>
                    <td>{{$order->employeeToHtml()}}</td>
                    <td>{{$order->employerToHtml()}}</td>
                    <td>{{$order->text}}</td>
                    <td>{{empty($order->transaction_price) ? '未成交' : $order->transaction_price}}</td>
                    <td>{{$order->statusToHtml()}}</td>
                    <td>{{isset($order->created_at) ? $order->created_at->format('Y-m-d H:i') : '无'}}</td>
                    <td class="do">
                    <a href="{{ url('/admin/order/details',['id' => $order->id]) }}" ><span class="entypo-info"></span>订单详情</a>
                    </td>
                </tr>
                @endforeach
                <tr><td colspan="9">
                    <div class="table-actions row">
                        <div class="pull-right">{{$orders->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                    </div>
                </td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
<link rel="stylesheet" href="{{ asset('/backend/js/daterangepicker/daterangepicker-bs3.css') }}">
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
<script src="{{ asset('/backend/js/daterangepicker/moment.min.js') }}" ></script>
<script src="{{ asset('/backend/js/daterangepicker/daterangepicker.js') }}" ></script>
@stop