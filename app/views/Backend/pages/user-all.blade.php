@extends('Backend.layout')
@section('page_title')
<h1>所有用户</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".submit-btn" id="status" name="status">
                        <option value='' >--状态--</option>
                        <option value='{{User::STATUS_OK}}' @if(Input::get('status') == User::STATUS_OK) selected @endif>正常</option>
                        <option value='{{User::STATUS_DISABLED}}' @if(Input::get('status') == User::STATUS_DISABLED) selected @endif>已禁用</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".submit-btn" name="role">
                        <option value='' >--角色--</option>
                        <option value='{{User::NEWBEE}}' @if(Input::get('role') !== '' && Input::get('role') == User::NEWBEE) selected @endif>普通用户</option>
                        <option value='{{User::FREEMAN}}' @if(Input::get('role') !== '' && Input::get('role') == User::FREEMAN) selected @endif>自由人</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="time_range" class="form-control daterange" value="{{Input::get('time_range')}}" placeholder="注册时间"/>
                </div>
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="昵称/手机号">
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
                    <th>头像</th>
                    <th>昵称</th>
                    <th>手机号</th>
                    <th>角色</th>
                    <th>状态</th>
                    <th>注册时间</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($users))
                <tr>
                    <td colspan="8">无数据</td>
                </tr>
                @else
                @foreach($users as $user)
                <tr>
                    <td>
                        <input type="checkbox" name="id" class="chkbox" value="{{$user->id}}">
                    </td>
                    <td class="td-id">{{$user->id}}</td>
                    <td><img src="{{$user->avatar()}}" height="25"></td>
                    <td>{{$user->nickname}}</td>
                    <td>{{empty($user->mobile) ? '无' : $user->mobile}}</td>
                    <td>{{$user->roleToHtml()}}</td>
                    <td>{{$user->statusToHtml()}}</td>
                    <td>{{isset($user->created_at) ? $user->created_at->format('Y-m-d H:i') : '无'}}</td>
                    <td class="do">
                        <a href="{{ url('/admin/user/certify',['id'=>$user->id]) }}" ><span class="entypo-pencil"></span>添加认证</a>
                        <a href="{{ url('/admin/user/services',['id'=>$user->id]) }}" ><span class="entypo-pencil"></span>添加服务</a>
                        @if($user->locked())
                        <a href="{{ url('/admin/user/update-status',['id'=>$user->id, 'status' =>  User::STATUS_OK]) }}"><span class="entypo-cw"></span>恢复</a>
                        @else
                        <a class="red" href="{{ url('/admin/user/update-status',['id'=>$user->id, 'status' => User::STATUS_DISABLED]) }}"><span class="entypo-cancel"></span>禁用</a>
                        @endif
                        <a class="red" onclick="return confirm('操作不可恢复，确认删除么？');" href="{{ url('/admin/user/delete',['id' => $user->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
                        <a href="{{ url('/admin/user/info',['id'=>$user->id]) }}" ><span class="entypo-eye"></span>查看详情</a>
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