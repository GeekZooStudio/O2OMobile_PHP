@extends('Backend.layout')
@section('page_title')
<h1>提现列表 <!--<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/user/new')}}" class="btn btn-info">新建</a>--></h1>
<style type="text/css">
    <style type="text/css">
    /*屏蔽背景层*/
    #divMsgBack
    {
        display:;
        position:fixed;
        _position:absolute;
        top:0px;
        left:0px;
        width:100%;
        height:100%;
        background-color:#fff;
        background:-moz-radial-gradient(50% 50%, #fff, #000);/*gradient for firefox*/
        background:-webkit-radial-gradient(50% 50%, #fff, #000);/*new gradient for webkit */
        background:-webkit-gradient(radial, 0 50%, 0 50%, from(#fff), to(#000));/*the old grandient for webkit*/
        opacity:0.5;
        filter:Alpha(opacity=50);
        _display:block;
        _left:-10000px;
        z-index:9999;
    }
    
    /*弹出层*/
    #divMsg
    {
        display:none;
        position:fixed;
        border:solid 5px gray;      
        background-color:white;
        top:50%;
        left:50%;
        width:500px;
        height:100px;
        margin-left:-250px;
        margin-top:-150px;
        padding:10px;
        line-height:21px;
        border-radius:15px;
        -moz-border-radius:15px;
        box-shadow:0 5px 27px rgba(0,0,0,0.3);
        -webkit-box-shadow:0 5px 27px rgba(0,0,0,0.3);
        -moz-box-shadow:0 5px 27px rgba(0,0,0,0.3);
        _position:absolute;
        _display:block;
        _left:-10000px;
        z-index:10000;
    }
    
    /*关闭按钮*/
    #divMsg #aClose
    {
        font-family:Tahoma;
        border:solid 2px #ccc;
        padding:0px 5px;
        text-decoration:none;
        font-size:12px;
        color:blue;
        position:absolute;
        right:5px;
        top:5px;
        line-height:14px;
        height:14px;
        width:7px;
        border-radius:14px;
        -moz-border-radius:14px;
        background-color:white;
    }
    
    #divMsg #aClose:hover
    {
        border:solid 2px red;
        color:red;
    }   
    
    /*设置显示*/
    #msgBody #divMsgBack{
        display:block;
        _left:0px;
    }
    #msgBody #divMsg{
        display:block;
        _left:50%;
    }
    #msgBody{
        _overflow:hidden;
    }
    
    </style>
</style>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <select class="selectboxit auto-submit" data-target=".submit-btn" name="state">
                        <option value='' >--提现状态--</option>
                        <option value='un' @if(Input::get('state') !== '' && Input::get('state') == 'un') selected @endif>处理中</option>
                        <option value='{{Withdraw::WITHDRAW_SUCC}}' @if(Input::get('state') !== '' && Input::get('state') == Withdraw::WITHDRAW_SUCC) selected @endif>提现成功</option>
                        <option value='{{Withdraw::WITHDRAW_FAILED}}' @if(Input::get('state') !== '' && Input::get('state') == Withdraw::WITHDRAW_FAILED) selected @endif>提现失败</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="text" name="time_range" class="form-control daterange" value="{{Input::get('time_range')}}" placeholder="提现时间"/>
                </div>
                <div class="col-md-3">
                    <input type="text" name="keyword" class="form-control" value="{{Input::get('keyword')}}" placeholder="会员昵称">
                </div>
                <div class="col-md-1">
                    <input type="submit" class="submit-btn btn btn-blue" value="搜索" />
                </div>
            </form>
        </div>
        <hr class="clearfix">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" class="select-all" data-target=".chkbox">
                    </th>
                    <th>ID</th>
                    <th>会员昵称</th>
                    <th>提现金额</th>
                    <th>提现状态</th>
                    <th>备注</th>
                    <th>提现时间</th>
                    <th class="do">操作</th>
                </tr>
            </thead>
            <tbody>
                @if(!count($withdraws))
                <tr>
                    <td colspan="8">无数据</td>
                </tr>
                @else
                @foreach($withdraws as $withdraw)
                <tr>
                    <td>
                        <input type="checkbox" name="id" class="chkbox" value="{{$withdraw->id}}">
                    </td>
                    <td class="td-id">{{$withdraw->id}}</td>
                    <td>{{$withdraw->userToHtml()}}</td>
                    <td>{{$withdraw->amount}}</td>
                    <td>{{$withdraw->stateToHtml()}}</td>
                    <td>{{$withdraw->note}}</td>
                    <td>{{isset($withdraw->created_at) ? $withdraw->created_at->format('Y-m-d H:i') : '无'}}</td>
                    <td class="do">
                        @if($withdraw->state == 0)
                        <a href="{{ url('/admin/withdraw/money') }}?id={{$withdraw->id}}&state={{Withdraw::WITHDRAW_SUCC}}" style="color:#348FCC">提现成功</a>
                        <a href="#" style="color:#CF4339" class="Withdraw_failed" id="{{$withdraw->id}}" state="{{Withdraw::WITHDRAW_FAILED}}">提现失败</a>
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr><td colspan="9">
                    <div class="table-actions row">
                        <div class="pull-right">{{$withdraws->appends(Request::except('page'))->links('Backend.includes.pager')}}</div>
                    </div>
                </td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div id="divMsgBack"></div>
<div id="divMsg">
    <input maxlength="240" class="autogrow form-control" placeholder="原因" />
    <button type="button" class="btn btn-success" style="margin:10px 120px;">提交</button>
    <button type="button" class="btn btn-grey Withdraw_cancel">取消</button>
</div>
<script type="text/javascript">
    $(function(){
        var id    = 0;
        var state = 0;
        $(".Withdraw_failed").click(function(){
            $('#divMsg').show();
            id    = $(this).attr('id');
            state = $(this).attr('state');
            return false;
        });

        $(".btn-success").click(function(){
            var note = $(".autogrow").val();
            if(!note){
                alert('请输入原因');
                return false;
            }
            window.location.href = '/admin/withdraw/money?id='+id+'&state='+state+'&note='+note;
        });
  
        $(".Withdraw_cancel").click(function(){
            $('#divMsg').hide();
            return false;
        });
    });
</script>
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