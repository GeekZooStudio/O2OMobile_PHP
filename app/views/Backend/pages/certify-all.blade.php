@extends('Backend.layout')
@section('page_title')
<h1>认证列表 <span class="middle-sep mg-x-10"></span><a href="{{url('/admin/certify/new')}}" class="btn btn-info">新建</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>服务名字</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($certifys))
                <tr>
                    <td colspan="8">无数据</td>
                </tr>
                @else
                @foreach($certifys as $certify)
                <tr>
                    <td class="td-id">{{$certify->id}}</td>
                    <td>{{$certify->name}}</td>
                    <td class="do">
                        <a href="{{ url('/admin/certify/edit',['id'=>$certify->id]) }}" ><span class="entypo-pencil"></span>修改</a>
                        <a class="red" onclick="return confirm('此操作会解除用户对此认证的认证且不可恢复，确认删除么？');" href="{{ url('/admin/certify/delete',['id' => $certify->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
                    </td>
                </tr>
                @endforeach
                <tr><td colspan="9">
                    <div class="table-actions row">
                        <div class="pull-right">{{$certifys->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                    </div>
                </td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@stop