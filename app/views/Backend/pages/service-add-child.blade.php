@extends('Backend.layout')
@section('page_title')
<h1>为“{{$ser->name}}”添加下级分类服务<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/service/all')}}" class="btn btn-info">返回列表</a></h1>
@stop
@section('content')
<div class="row">
    <form action="{{url('/admin/service/create')}}" method="post" accept-charset="utf-8" class="form-horizontal">
        <input type="hidden" name="parent_id" value="{{$id}}" >
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 名称</label>
            <div class="col-sm-5">
                <input type="text" name="name" value="{{Input::old('name')}}" class="form-control" placeholder="">
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star"></i> 简介</label>
            <div class="col-sm-5">
                <textarea name="desc" maxlength="140" class="autogrow form-control" placeholder="" style="overflow: hidden; word-wrap: break-word; resize: horizontal; height: 72px;">{{Input::old('desc')}}</textarea>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star"></i> 排序</label>
            <div class="col-sm-5">
                <input type="text" style="width:100px;" name="usort" value="@if(Input::old('usort')) {{Input::old('usort')}} @else 0 @endif" class="form-control" placeholder="请输入数字，数字越大越靠后">
            </div>
        </div>
        <div class="clearfix">
            <label class="col-sm-2 control-label"> LOGO</label>
            <div class="col-sm-5">
                <div class="input-group">
                  <input type="url" id="img-url" data-preview="#photo-view-img" placeholder="填写图片URL或者选择本地文件上传" name="imgurl" value="{{Input::old('imgurl')}}" class="url-image form-control">
                  <span class="input-group-btn">
                  <button class="btn btn-white file-uploader" data-option="{urlContainer:'#img-url', accept:{extensions: 'jpeg,jpg,png,gif,bmp', mimeTypes:'image/*'},maxSize:200}" type="button"><i class="entypo-popup"></i> 本地文件</button>
                  </span>
                </div>
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