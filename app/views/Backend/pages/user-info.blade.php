@extends('Backend.layout')
@section('page_title')
<h1>用户详情<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/user/all')}}" class="btn btn-info">返回列表</a></h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <form action="" method="post" accept-charset="utf-8" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户ID：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->id}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户头像：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <img src="{{$user->avatar()}}" width="50px" />
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">手机号：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->mobile}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">用户昵称：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->nickname}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">真实姓名：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->name}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    @if ($user->role == User::FREEMAN)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">银行卡号：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                           <span>{{$user->bankcard}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">身份证：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                           <span>{{$user->identity_card}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    @endif
                    <div class="form-group">
                        <label class="col-sm-2 control-label">余额：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->balance}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">签名：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->signature}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">简介：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->brief}}</span>
                        </div>
                    </div>
                    <hr class="clearfix" />
                    <div class="form-group">
                        <label class="col-sm-2 control-label">加入时间：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            <span>{{$user->created_at}}</span>
                        </div>
                    </div>
                </form>

            </div>

        </div>
        
    </div>
    <div class="col-md-4 ugc-tips">
      <div class="alert alert-info" style="max-height:836px;overflow:auto;">
        @foreach($data as $key=>$val)
            <h4><strong>{{$key}}</strong></h4>
            @foreach($val as $k=>$v)
            <p>{{$k}}月 &nbsp;&nbsp;成交{{$v}}笔</p>
            @endforeach
            <br>
        @endforeach
      </div>
    </div>
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
@stop