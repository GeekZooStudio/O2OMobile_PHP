@extends('Backend.layout')
@section('page_title')
<h1>“{{$ser->name}}”下的所有服务 <span class="middle-sep mg-x-10"></span><a href="{{url('/admin/service/addchild')}}?id={{$ser->id}}" class="btn btn-info">新建</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="select-all" data-target=".chkbox">
                    </th>
                    <th>ID</th>
                    <th>服务名字</th>
                    <th>服务ICON</th>
                    <th>状态</th>
                    <th>时间</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($services))
                <tr>
                    <td colspan="8">无数据</td>
                </tr>
                @else
                @foreach($services as $service)
                <tr>
                    <td>
                        <input type="checkbox" name="id" class="chkbox" value="{{$service->id}}">
                    </td>
                    <td class="td-id">{{$service->id}}</td>
                    <td>{{$service->name}}</td>
                    <td><img src="{{$service->imgurl}}" height="25"></td>
                    <td>{{$service->state}}</td>
                    <td>{{isset($service->created_at) ? $service->created_at : '无'}}</td>
                    <td class="do">
                        @if(!$isparent)
                        <a href="{{url('/admin/service/lookchild')}}?id={{$service->id}}" ><span class="entypo-eye"></span>查看子类</a>
                        <a href="{{url('/admin/service/addchild')}}?id={{$service->id}}" ><span class="entypo-plus"></span>添加子类</a>
                        @endif
                        <a class="red" onclick="return confirm('操作不可恢复，确认删除么？');" href="{{ url('/admin/service/delete',['id' => $service->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
                    </td>
                </tr>
                @endforeach
                <tr><td colspan="9">
                    <div class="table-actions row">
                        <div class="pull-right">{{$services->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                    </div>
                </td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@stop