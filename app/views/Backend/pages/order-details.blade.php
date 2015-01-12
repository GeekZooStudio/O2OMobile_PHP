@extends('Backend.layout')
@section('page_title')
<h1>订单详情<a href="{{url('/admin/order')}}" class="btn btn-info">返回列表</a>
</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <form action="{{url('/admin/user/create')}}" method="post" accept-charset="utf-8" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">订单ID：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->id}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">序列号：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->order_sn}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">服务类型：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->serviceToHtml()}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">雇主：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->employerToHtml()}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">雇员：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->employeeToHtml()}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">内容：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{empty($order->text) ? '无' : $order->text}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">VOICE：</label>
                        <div class="col-sm-8" style="padding-top:7px;color:#8A9697;">
                            @if(empty($order->voice))
                            无
                            @else
                            <embed width="270" height="30" type="application/x-shockwave-flash" wmode="transparent" src="{{$host}}/flash/player.swf?soundFile={{$host}}/uploadFile/voice{{$order->voice}}&amp;bg=0xeeeeee&amp;leftbg=0x99ff00&amp;lefticon=0x666666&amp;rightbg=0x666666&amp;rightbghover=0x99ff00&amp;righticon=0xffffff&amp;righticonhover=0xffffff&amp;text=0x666666&amp;slider=0x666666&amp;track=0xFFFFFF&amp;border=0x666666&amp;loader=0x99ff00&amp;autostart=&amp;loop=yes">
                            @endif
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">发单地址：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$location->name}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">约定价格：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            ￥{{$order->offer_price}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">成交价格：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{empty($order->transaction_price) ? '无' : $order->transaction_price}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">付款方式：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                        @if($order->transaction_price != '0.00')
                            @if($order->pay_code == Orders::PAY_ONLINE)
                                <span class="badge badge-danger">线上支付</span>
                            @else
                                <span class="badge badge-default">线下支付</span>
                            @endif
                        @else
                            <font color="grey">未知</font>
                        @endif
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">约定时间：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->appointment_time}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">接单时间：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->accept_time ? $order->accept_time : '无'}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">推送人数：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{$order->push_number}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">默认接单人：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{empty($order->default_receiver_id) ? '无' : $order->default_receiver_id}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">修改时间：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{isset($order->updated_at) ? $order->updated_at->format('Y-m-d H:i:s') : '无'}}
                        </div>
                    </div>
                    <hr class="clearfix" />

                    <div class="form-group">
                        <label class="col-sm-2 control-label">删除时间：</label>
                        <div class="col-sm-6" style="padding-top:7px;color:#8A9697;">
                            {{isset($order->deleted_at) ? $order->deleted_at->format('Y-m-d H:i:s') : '无'}}
                        </div>
                    </div>

                    
                </form>
            </div>
        </div>
    </div>
    <!-- /col-md-8-->
    <blockquote class="col-md-4">
        <p>雇主评论：<span style="font-size:12px;color:grey;">{{Comment::getCommnets($order->id, $order->employer)}}</span></p>
        <p>雇员评论：<span style="font-size:12px;color:grey;">{{Comment::getCommnets($order->id, $order->employee)}}</span></p>
        <p>订单状态：{{$order->statusToHtml()}}</p>
        <p>创建时间：{{isset($order->created_at) ? $order->created_at->format('Y-m-d H:i:s') : '无'}}</p>

    </blockquote>
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
@stop