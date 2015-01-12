/**
 * 文件上传控件
 *
 * 在网页上放一个或者多个button，加上class="file-uploader"与其它属性即可，同一个页面可以有多个实例
 * options:
 * {
 *     maxSize: 50 * 1024,//kb
 *     type:'photo',
 *     accept: null,
 *   }
 */

jQuery(document).ready(function(){
  var SERVER_HOST = $(".SERVER_HOST").val();
  console.log(SERVER_HOST);
  var SERVER_HOST = SERVER_HOST || 'http://ban.rj-geek.com/';
  $('#model-file-uploader').appendTo('body');
  $('.file-uploader').on('click', function(){
    var _this   = $(this);
    var uploaderModal = $('#model-file-uploader');
    if (undefined == WebUploader) {
      console.error('请先加载webUploader.js');
    };

    var options = {
      maxSize: 50 * 1024 * 1024,
      type:'photo',
      accept: null,
      thumbnailBox: null,
    };

    $.extend(options, eval('('+_this.data('option')+')') || {});

    uploaderModal.modal('show');
    
    var fileList   = $('#uploader-file-list'),
        picker     = $('#uploader-picker'),
        controlBtn = $('#uploader-ctlBtn'),
        state      = 'pending',
        uploader;


    uploader = WebUploader.create({

        // 不压缩image
        resize: false,

        // 文件接收服务端。
        //server: 'http://webuploader.duapp.com/server/fileupload.php',
        server: SERVER_HOST + '/file/upload?token=45a58e00e28c3831fe04fdf8aae70fd4&type='+options.type,

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#uploader-picker',

        accept: options.accept,
    });

    function resetModal(){
      picker.attr('disabled', false).show();
      fileList.empty();
      uploader.reset();
      controlBtn.attr('disabled', true);
    }
    resetModal();

    $('.cancel-btn').on('click', function(){
      resetModal();
      uploaderModal.modal('hide');
    });

    uploader.on('beforeFileQueued',function(file){
      if (file.size > options.maxSize * 1024) {
        uploader.reset();
        alert('文件不能大于' + (options.maxSize) + 'kb');

        return false;
      };
    });

    // 当有文件添加进来的时候
    uploader.on('fileQueued', 
    function(file) {
        fileList.append('<div id="' + file.id + '" class="item">' + 
        '<h4 class="info">' + file.name + '</h4>' + 
        '<p class="state">等待上传...</p>' + 
        '</div>');
        picker.attr('disabled', true).hide();
    });

    // 文件上传过程中创建进度条实时显示。
    uploader.on('uploadProgress', 
    function(file, percentage) {
        var $li = $('#' + file.id),
        $percent = $li.find('.progress .progress-bar');
        // 避免重复创建
        if (!$percent.length) {
            $percent = $('<div class="progress progress-striped active">' + 
            '<div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">' + 
            '</div>' + 
            '</div>').appendTo($li).find('.progress-bar');

        }

        $li.find('p.state').text('上传中...');

        $percent.css('width', percentage * 100 + '%');

    });

    uploader.on('uploadSuccess', 
    function(file) {
      $('#' + file.id).find('p.state').text('<span class="green">已上传</span>');
      uploaderModal.modal('hide');
    });

    uploader.on('uploadError', 
    function(file, reason) {
      $('#' + file.id).find('p.state').html('<span class="red">上传出错:'+reason+'</span>');
    });

    uploader.on('uploadComplete', 
    function(file) {
      $('#' + file.id).find('.progress').fadeOut();
    });

    uploader.on('uploadAccept', 
    function(object, ret) {
        console.log('uploadAccept', object, ret);
        if (ret.err == 0) {
          console.log($(options.urlContainer).attr('id'));
          if ($(options.urlContainer).length) {
            $(options.urlContainer).val(ret.src).change();
          };

          if (options.callback) {
            options.callback(_this, ret);
          };
          return true;
        };
        return false;
        //无论 true和false 都返回 http
    });

    uploader.on('all', 
    function(type) {
        if (type === 'startUpload') {
            state = 'uploading';

        } else if (type === 'stopUpload') {
            state = 'paused';

        } else if (type === 'uploadFinished') {
            state = 'done';

        }

        if (state === 'uploading') {
            controlBtn.text('上传中...').attr('disabled', true);
        } else {
            controlBtn.text('开始上传').attr('disabled', false);
        }

    });

    controlBtn.on('click', 
    function() {
        if (state === 'uploading') {
            uploader.stop();
        } else {
            uploader.upload();
        }
    });
  });
});
