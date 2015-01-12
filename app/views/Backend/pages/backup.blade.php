@extends('Backend.layout')
@section('page_title')
<h1>数据备份</h1>
@stop
@section('content')
<div class="row">
    <div class="col-md-12 form-group">
        <div class="table-filter col-md-12 row">
            <form action="" method="get" accept-charset="utf-8">
                <div class="col-md-2">
                    <select name="action" class="form-control selectboxit auto-submit" data-target=".filter-submit-button">
                        <option value="0"> -- 行为 -- </option>
                        <option value="添加" @if(Input::get('action') == '添加') selected @endif>添加</option>
                        <option value="编辑" @if(Input::get('action') == '编辑') selected @endif>编辑</option>
                        <option value="删除" @if(Input::get('action') == '删除') selected @endif>删除</option>
                        <option value="审核" @if(Input::get('action') == '审核') selected @endif>审核</option>
                    </select>             
                </div>              
                <span class="middle-sep pull-left"></span> 
                
                <div class="col-sm-4">
                    <input type="text" name="time_range" class="form-control daterange" value="{{Input::get('time_range')}}" placeholder="起止时间"/>
                </div>
                <div class="col-md-3">
                    <input name="user_id" autocomplete="on" value="{{Input::get('user_id')}}" placeholder="用户ID" class="form-control"/>            
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-info filter-submit-button">搜索</button>
                </div>
            </form>
        </div>
        <hr class="clearfix">
        <form action="{{url('/admin/log/do-more')}}" method="post" accept-charset="utf-8">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" class="select-all" data-target=".chkbox" />
                        </th>
                        <th>日期</th>
                        <th>文件名</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                @if(!count($filenames)) 
                <tr class="first tcenter">
                    <td colspan="4">暂无数据</td>
                </tr>
                @else
                @foreach($filenames as $foldername => $filename)
                    <!-- row -->
                    <tr class="{{ $i == 0 ? 'first' : '' }}">
                        <td>
                            <input type="checkbox" name="id[]" class="chkbox" value="{{$log->id}}">
                        </td>
                        <td>
                        </td>
                        <td>{{$foldername}}</td>
                        <td>{{$filename}}</td>
                        <td class="do">
                  <!--           <a class="red" href="{{url('/admin/log/delete', array('id' => $log->id))}}" onclick="return confirm('确认删除吗？')">
                                <i class="entypo-cancel-circled"></i>
                                删除
                            </a> -->
                        </td> 
                    </tr>
                    <!-- row -->
                @endforeach
                @endif
                </tbody>
            </table>
            <div class="table-actions row">
            <!--     <input type="hidden" name="action" value="delete">
                <button type="submit" class="btn btn-danger btn-do-more">批量删除</button> -->
                <!-- <div class="pull-right">{{$logs->appends(Request::except('page'))->links('Backend.includes.pager')}}</div> -->
            </div>
        </form>
    </div>
    <!-- end col-md-12 -->
</div>
@stop

@section('page_css')
<link rel="stylesheet" href="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.css') }}">
<link rel="stylesheet" href="{{ asset('/backend/js/daterangepicker/daterangepicker-bs3.css') }}">
@endsection

@section('page_js')
<script src="{{ asset('/backend/js/selectboxit/jquery.selectBoxIt.min.js') }}" ></script>
<script src="{{ asset('/backend/js/daterangepicker/moment.min.js') }}" ></script>
<script src="{{ asset('/backend/js/daterangepicker/daterangepicker.js') }}" ></script>
@endsection