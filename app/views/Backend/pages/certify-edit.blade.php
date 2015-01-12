@extends('Backend.layout')
@section('page_title')
<h1>编辑“{{$certify->name}}”<span class="middle-sep mg-x-10"></span><a href="{{ url('/admin/certify/all') }}" class="btn btn-info">返回列表</a></h1>
@stop
@section('content')
<div class="row">
    <form action="{{url('/admin/certify/update')}}" method="post" accept-charset="utf-8" class="form-horizontal">
        <input type="hidden" name="id" value="{{$certify->id}}" class="form-control" placeholder="">
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 名称</label>
            <div class="col-sm-5">
                <input type="text" name="name" value="{{$certify->name}}" class="form-control" placeholder="" placeholder="4个字内" maxLength="4">
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