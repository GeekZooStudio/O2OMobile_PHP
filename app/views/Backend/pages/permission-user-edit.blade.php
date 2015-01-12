@extends('Backend.layout')
@section('page_title')
<h1>编辑管理员<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/permission/user')}}" class="btn btn-info">返回列表</a>
</h1>
@stop
@section('content')
<div class="row">
    <form action="{{url('/admin/permission/user-create')}}" method="post" accept-charset="utf-8" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 登录名</label>
            <div class="col-sm-5">
                <input type="text" name="username" class="form-control" value="{{$user->username}}" placeholder="管理员登录名称">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 权限组</label>
            <div class="col-sm-5">
                <select class="selectboxit auto-submit" data-target=".filter-submit-button" name="group_id">
                    <option value='' >--选择所属权限组--</option>
                    @foreach($groups as $group)
                    <option value='{{$group->id}}' @if($group->id == $user->group_id) selected @endif>{{$group->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star"></i> 密码</label>
            <div class="col-sm-5">
                <input type="password" name="password" class="form-control" value="" placeholder="不填写则不修改密码">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star"></i> 确认密码</label>
            <div class="col-sm-5">
                <input type="password" name="rePassword" class="form-control" value="" placeholder="确认密码">
            </div>
        </div>
        <hr class="clearfix">
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-success">提交</button>
                <input type="hidden" name="id" value="{{$user->id}}">   
            </div>
        </div>
    </form>
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
@stop