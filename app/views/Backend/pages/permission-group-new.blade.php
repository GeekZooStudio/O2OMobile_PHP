@extends('Backend.layout')
@section('page_title')
<h1>添加权限组<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/permission/group')}}" class="btn btn-info">返回列表</a>
</h1>
@stop
@section('content')
<div class="row">
    <form action="{{url('/admin/permission/group-create')}}" method="post" accept-charset="utf-8" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 名称</label>
            <div class="col-sm-5">
                <input type="text" name="name" class="form-control" value="{{Input::old('name')}}" placeholder="权限组名称">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 权限</label>
            <div class="col-sm-8">
                <div class="select-all-permission checkbox">
                    <label><input type="checkbox" name="" value="{{Input::old('name')}}" class="select-all" data-target=".chkbox">全部</label>
                </div>
                @foreach(Config::get('menu') as $key => $permission)
                    @if (isset($permission['permission']) && $permission['permission'] == '*')
                    <!-- 默认*权限不需要设置 -->
                    @else
                    <fieldset>
                        <legend>{{$permission['name']}}</legend>
                    </fieldset>
                    <div class="permissions">
                        @if (count($permission['submenu'])) 
                            @foreach($permission['submenu'] as $subKey => $child)
                            <div class="checkbox">
                                <label><input type="checkbox" class="chkbox" name="permission[]" value="{{$child['permission']}}">{{$child['name']}}&nbsp;&nbsp;</label>
                            </div>
                            @endforeach
                        @else
                            <div class="checkbox">
                                <label><input type="checkbox" class="chkbox" name="permission[]" value="{{$permission['permission']}}">全部</label>
                            </div>
                        @endif
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        <hr class="clearfix">
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <button type="submit" class="btn btn-success">提交</button>
                
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