@extends('Backend.layout')
@section('page_title')
<h1>添加消息<span class="middle-sep mg-x-10"></span><a href="{{url('/admin/message')}}" class="btn btn-info">返回列表</a>
</h1>
@stop
@section('content')
<div class="row">
    <form action="{{url('/admin/message/create')}}" method="post" accept-charset="utf-8" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 内容</label>
            <div class="col-sm-5">
                <textarea name="message" id="message_content" maxlength="40" class="autogrow form-control" placeholder="推送消息不宜过长，请尽量精简，最长40字符">{{Input::old('message')}}</textarea>
            </div>

            <div class="col-md-4 ugc-tips">
              <div class="alert alert-info">
                <h4>添加消息说明：</h4>
                <p>1. 系统消息推送所有用户，无需填写用户ID</p>
                <p>2. 如果定向发送消息，请选择个人消息.必须填写用户ID</p>
                <p>3. 推送内容必须填写，URL选填</p>
                <br/>
                <h4>推送消息内容样例：</h4>
                <p><strong>1. 温馨提醒:关于天气</strong></p>
                <p>（1）亲，今天天热，驰骋在外，注意多喝水，健康安全放首位！</p>
                <p>（2）亲，今天有雨哦，带好雨具，外出更从容！</p>
                <p>（3）亲，今儿柳絮满天，如有不适，要提前备好口罩哦！</p>
                <p>（4）亲，今天天气骤变，提前防范哦，有备无患！</p>
                <br/>
                <p><strong>2. 温馨提醒:关于开车</strong></p>
                <p>（1）亲，因天气原因，路况有点小糟糕，不急不急，安全行驶最重要。</p>
                <p>（2）亲，今天大家都有事，堵车路上有你有我，堵车不堵气，安全驾驶是王道！</p>
                <br/>
                <p><strong>3. 温馨提醒:关于学习</strong></p>
                <p>（1）活到老，学到老，加油哦！我看好你哦！</p>
                <p>（2）GOOD GOOD STUDY,DAY DAY UP! </p>
                <br/>
                <p><strong>4. 温馨提醒:关于看护</strong></p>
                <p>（1）真心、细心做好事，我们就是很棒！</p>
                <p>（2）亲，今天累了吧，回家洗个热水澡，疲劳立刻跑，挣钱开心，休息万岁！</p>
                <br/>
                <p><strong>5. 温馨提醒:关于办公</strong></p>
                <p>（1）闲来发挥小能量，我们会越来越棒哦！</p>
                <p>（2）接了小活有点累，但挣点闲钱好开心哦！</p>
              </div>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">URL</label>
            <div class="col-sm-5">
                <input type="text" name="url" value="{{Input::old('url')}}" class="form-control" placeholder="URL">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"><i class="required-star">*</i> 类型</label>
            <div class="col-sm-3">
                <select name="action" id="action" class="selectboxit action-changer">
                    <option data-type="system" value="{{Message::SYSTEM}}" selected>系统消息</option>
                    <option data-type="other" value="{{Message::OTHER}}">个人消息</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label"> 指定用户</label>
            <div class="col-sm-5">
                <div class="input-group">
                    <input type="text" name="target_user" id="zhiDingId" value="{{Input::old('target_user')}}" class="form-control" placeholder="填写用户ID" disabled>
                    <!--
                    <span class="input-group-btn">
                        <button class="btn btn-white typeahead-search-btn" data-target="#user-search-box" type="button"><i class="entypo-popup"></i> <span>搜索</span></button>
                    </span>
                    -->
                </div>
                <!-- typeahead 
                <input type="text" id="user-search-box" placeholder="ID、用户名、email等关键字皆可搜索, 输入关键字后等待结果显示" data-target="[name='target_user']" value="" class="serchbox form-control typeahead" data-remote="{{url('admin/user/all')}}?keyword=%QUERY" data-view-callback="userTpl" data-empty-string="未找到相关用户" displayKey="id">
                -->
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">立即推送</label>
            <div class="col-sm-5">
                <div class="checkbox">
                <label><input type="checkbox" name="push_now" value="1">是</label>
                </div>
            </div>
        </div>
        <hr class="clearfix">
        <div class="form-group">
            <label class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
                <button type="submit" id="submit-btn" class="btn btn-success">提交</button>
            </div>
        </div>
    </form>
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
@stop

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
<script>
    $(function(){
        $('#submit-btn').on('click', function() {
            if($("#action").val() == "{{Message::OTHER}}" && $("#zhiDingId").val() == ''){
                alert("个人消息必须填写用户ID");
                return false;
            }
            if($('#message_content').val() == ''){
                alert("请填写推送内容");
                return false;
            }
            if ($('#message_content').val().length && $('#action').val().length && $('#object').val().length) {
                if(!confirm('消息创建之后不可再修改，确认创建？')) {
                    return false;
                }
            };
        });

        $(".selectboxit").change(function() {
            if ($(this).val() != 1) {
                $('input[name="target_user"]').prop('disabled', false);
            }
            else {
                $('input[name="target_user"]').prop('disabled', true);
            }
        });
    });
</script>
@stop