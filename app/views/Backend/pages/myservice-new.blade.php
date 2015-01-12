@extends('Backend.layout')
@section('page_title')
<h1>为“{{$users->nickname}}”添加服务<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/user/all')}}" class="btn btn-info">返回列表</a></h1>
@stop
@section('content')
<div class="row">
    <h3>“{{$users->nickname}}”已有的服务</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>服务</th>
                <th>图标</th>
                <th class="do">操作</th>
            </tr>
        </thead>
        <tbody>
            @if(!count($my_services))
            <tr>
                <td colspan="8">无数据</td>
            </tr>
            @else
            @foreach($my_services as $my_service)
            <tr>
                <td class="td-id">{{$my_service->id}}</td>
                <td>{{$my_service->myserviceToHtml()}}</td>
                <td><img src="" height="25"></td>
                <td class="do">
                    <a class="red" href="{{ url('/admin/myservice/del',['id' => $my_service->id]) }}" ><span class="entypo-cancel-circled"></span>取消服务</a>
                </td>
            </tr>
            @endforeach
            <tr><td colspan="9">
                <div class="table-actions row">
                    <div class="pull-right">{{$my_services->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                </div>
            </td></tr>
            @endif
        </tbody>
    </table>
<hr class="clearfix">
    <form action="{{url('/admin/myservice/add')}}" method="post" accept-charset="utf-8" class="form-horizontal">
                <input type="hidden" name="user_id" value="{{$users->id}}" class="form-control" placeholder="">
        <div class="form-group">
            <label class="col-sm-2 control-label">服务列表</label>
            <div class="col-sm-3">
                <select name="services_id" class="selectboxit">
                    @foreach($services as $service)
                    <option value="{{$service->id}}" selected >{{$service->name}}</option>
                    @endforeach
                </select>
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


@include('Backend.includes.fileuploader')
@stop

@section('page_js')
@stop