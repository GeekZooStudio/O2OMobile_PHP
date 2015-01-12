@extends('Backend.layout')
@section('page_title')
<h1>所有权限组<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/permission/group-new')}}" class="btn btn-info">新建</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".filter-submit-button" name="sort">
                        <option value='' >--排序--</option>
                        <option value='created_at-DESC' @if(Input::get('sort') == 'created_at-DESC') selected @endif>创建时间 - ↓</option>
                        <option value='created_at-ASC' @if(Input::get('sort') == 'created_at-ASC') selected @endif>创建时间 - ↑</option>
                    </select>
                </div>
                <span class="middle-sep pull-left"></span>
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="标题关键字">
                </div>
                <div class="col-md-1">
                    <input type="submit" class="filter-submit-button btn btn-blue" value="搜索" />
                </div>
            </form>
        </div>
        <hr class="clearfix">
        <form action="{{url('/admin/permission/do-more')}}" method="" accept-charset="utf-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="select-all" data-target=".chkbox">
                        </th>
                        <th class="td-id">ID</th>
                        <th class="">组名</th>
                        <th class="col-md-2">创建时间</th>
                        <th class="do">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!count($groups))
                    <tr>
                        <td colspan="5">无数据</td>
                    </tr>
                    @endif
                    @foreach($groups as $group)
                    <tr>
                        <td>
                            <input type="checkbox" name="id[]" class="chkbox" value="{{$group->id}}">
                        </td>
                        <td>{{$group->id}}</td>
                        <td>{{$group->name}}</td>
                        <td>{{$group->created_at}}</td>
                        <td class="do">
                            @if(!is_array($group->permissions) && $group->permissions == '*')
                            &nbsp;
                            @else
                            <a href="{{ url('/admin/permission/group-edit',['id'=>$group->id]) }}" ><span class="entypo-pencil"></span>编辑</a>
                            <a class="red" onclick="return confirm('删除后该组下用户只有默认权限，删除么？');" href="{{ url('/admin/permission/group-delete',['id'=>$group->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="table-actions row">
                <div class="pull-right">{{$groups->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
            </div>
        </form>
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