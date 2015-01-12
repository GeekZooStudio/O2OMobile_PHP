<div class="modal fade" id="model-file-uploader" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">上传文件</h4>
            </div>
            
            <div class="modal-body">
                <div class="uploader">
                    <!--用来存放文件信息-->
                    <div id="uploader-file-list" class="uploader-list"></div>
                    <div class="btns">
                        <div id="uploader-picker" class="btn btn-default">选择文件</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default cancel-btn" data-dismiss="modal">取消</button>
                <button id="uploader-ctlBtn" class="btn btn-info">开始上传</button>
            </div>
        </div>
    </div>
</div>
<input type="hidden" class="SERVER_HOST" value="{{Config::get('app.url')}}">
<script src="{{asset('/backend/js/webuploader.html5only.js')}}" type="text/javascript" ></script>
<script src="{{asset('/backend/js/fileuploader.js')}}" type="text/javascript" ></script>