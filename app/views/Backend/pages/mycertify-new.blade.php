@extends('Backend.layout')
@section('page_title')
<h1>为“{{$users->nickname}}”添加认证<span class="middle-sep mg-x-10"></span><a href="{{ asset('/admin/user/all') }}" class="btn btn-info">返回列表</a></h1>
@stop
@section('content')
<div class="row">
    <h3>“{{$users->nickname}}”已有的认证</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>认证</th>
                <th class="do">操作</th>
            </tr>
        </thead>
        <tbody>
            @if(!count($my_certifys))
            <tr>
                <td colspan="8">无数据</td>
            </tr>
            @else
            @foreach($my_certifys as $my_certify)
            <tr>
                <td class="td-id">{{$my_certify->id}}</td>
                <td>{{$my_certify->certifyToHtml()}}</td>
                <td class="do">
                    <a class="red" href="{{ url('/admin/mycertify/del',['id' => $my_certify->id]) }}" ><span class="entypo-cancel-circled"></span>取消认证</a>
                </td>
            </tr>
            @endforeach
            <tr><td colspan="9">
                <div class="table-actions row">
                    <div class="pull-right">{{$my_certifys->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                </div>
            </td></tr>
            @endif
        </tbody>
    </table>
<hr class="clearfix">
    <form action="{{url('/admin/mycertify/add')}}" method="post" accept-charset="utf-8" class="form-horizontal">
                <input type="hidden" name="user_id" value="{{$users->id}}" class="form-control" placeholder="">
        <div class="form-group">
            <label class="col-sm-2 control-label">新认证</label>
            <div class="col-sm-3">
                <select name="certify_id" class="selectboxit">
                    @foreach($certifys as $certify)
                    <option value="{{$certify->id}}" selected >{{$certify->name}}</option>
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