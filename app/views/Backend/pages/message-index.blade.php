@extends('Backend.layout')
@section('page_title')
<h1>所有消息<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/message/new')}}" class="btn btn-info">新建</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="内容关键字">
                </div>
                <div class="col-md-1">
                    <input type="submit" class="filter-submit-button btn btn-blue" value="搜索" />
                </div>
            </form>
        </div>
        <hr class="clearfix"/>
        <form action="{{url('/admin/message/do-more')}}" method="post" accept-charset="utf-8">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="select-all" data-target=".chkbox">
                        </th>
                        <th class="">内容</th>
                        <th>目标用户</th>
                        <th>状态</th>
                        <th class="col-md-2">创建时间</th>
                        <th class="do">操作</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!count($messages))
                    <tr>
                        <td colspan="6">无数据</td>
                    </tr>
                    @endif
                    @foreach($messages as $message)
                    <tr>
                        <td>
                            <input type="checkbox" name="id[]" class="chkbox" value="{{$message->id}}">
                        </td>
                        <td>{{$message->content}}</td>
                        <td>{{!empty($message->user_id) ? trim($message->user_id, ',') : '全部用户'}}</td>
                        <td>{{$message->statusToHtml()}}</td>
                        <td>{{$message->created_at ? $message->created_at->format('Y-m-d H:i'):'无'}}</td>
                        <td class="do">
                            <a href="{{ url('/admin/message/push',['id'=>$message->id]) }}" ><span class="entypo-right"></span>@if($message->is_pushed == Message::STATUS_OK)再次@endif推送</a>
                            <a class="red" onclick="return confirm('操作不可恢复，确认删除么？');" href="{{ url('/admin/message/delete',['id'=>$message->id]) }}" ><span class="entypo-cancel-circled"></span>删除</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @if(count($messages))
            <div class="table-actions row">
                <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger btn-do-more">批量删除</button>
                <div class="pull-right">{{$messages->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
            </div>
            @endif
        </form>
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