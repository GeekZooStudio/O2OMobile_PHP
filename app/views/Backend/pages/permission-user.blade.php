@extends('Backend.layout')
@section('page_title')
<h1>所有管理员<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/permission/user-new')}}" class="btn btn-info">新建</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".submit-btn" name="group">
                        <option value=''>--所属权限组--</option>
                        <option value='100'>未分配组</option>
                        @foreach($groups as $group)
                        <option value='{{$group->id}}'>{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="昵称">
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
                    <th>名称</th>
                    <th>所属权限组</th>
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
                    <td>{{$user->username}}</td>
                    <td>@if(isset($user->permissionGroup)){{$user->permissionGroup->name}}@else未分配组@endif</td>
                    <td>{{isset($user->created_at) ? $user->created_at->format('Y-m-d H:i') : '无'}}</td>
                    <td class="do">
                        <a href="{{ url('/admin/permission/user-edit',['id'=>$user->id]) }}" ><span class="entypo-pencil"></span>编辑</a>
                        <a class="red" onclick="return confirm('操作不可恢复，确认删除么？');" href="{{ url('/admin/permission/user-delete',['id' => $user->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
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
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
<script src="{{ asset('/backend/js/daterangepicker/moment.min.js') }}" ></script>
@stop