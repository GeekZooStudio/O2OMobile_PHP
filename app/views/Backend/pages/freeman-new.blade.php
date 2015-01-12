@extends('Backend.layout')
@section('page_title')
<h1>自由人申请列表</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-3">
                    <input type="text" name="time_range" class="form-control daterange" value="{{Input::get('time_range')}}" placeholder="时间"/>
                </div>
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="姓名/昵称/手机号">
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
                    <!--<th>
                        <input type="checkbox" class="select-all" data-target=".chkbox">
                    </th>-->
                    <th>ID</th>
                    <th>姓名</th>
                    <th>性别</th>
                    <th>头像</th>
                    <th>手机</th>
                    <th>身份证</th>
                    <th>银行卡</th>
                    <th>注册时间</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($users))
                <tr>
                    <td colspan="9">无数据</td>
                </tr>
                @else
                @foreach($users as $user)
                <tr>
                    <!--<td>
                        <input type="checkbox" name="id" class="chkbox" value="{{$user->id}}">
                    </td>-->
                    <td class="td-id">{{$user->id}}</td>
                    <td><span class="badge badge-success">{{$user->name}}</span></td>
                    <td>@if ($user->gender == 0) 男 @else 女 @endif</td>
                    <td><a href='{{$user->avatar()}}' target="_blank"><img src="{{$user->avatar()}}" height="50"></a></td>
                    <td>{{empty($user->mobile) ? '无' : $user->mobile}}</td>
                    <td>{{$user->identity_card}}</td>
                    <td>{{$user->bankcard}}</td>
                    <td>{{isset($user->created_at) ? $user->created_at->format('Y-m-d H:i') : '无'}}</td>
                    <td class="do">
                        <a href="{{ url('/admin/user/freemanok',['id'=>$user->id]) }}" ><span class="entypo-pencil"></span>审核通过</a>
                        <a class="red" href="{{ url('/admin/user/freemanno',['id' => $user->id]) }}" ><span class="entypo-cancel-circled"></span>审核不通过</a>
                    </td>
                </tr>
                @endforeach
                <tr><td colspan="9">
                    <div class="table-actions row">
                        <div class="pull-right">{{$users->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
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