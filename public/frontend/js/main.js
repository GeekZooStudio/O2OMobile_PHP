// Place any jQuery/helper plugins in here.
var ie678 = !-[1,];
function debounce(func, wait, immediate) {
    var timeout;
    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

$.fn.extend({
    J_select: function(opts){
        if(!this[0])return;

        return this.each(function(){
            var select = $(this),
                unit = select.closest('.unit'),
                combo = select.parent().addClass('J_combo'),
                comboList = '', textCurrent = '', txt = '',
                build = function (){
                    var options = select.find("option"),
                        selVal = select.val(),
                        txtWrap = "<span class='txtWrap'><i class='arrow'></i><span class='txt'>";

                    comboList && comboList.remove();
                    comboList = '<div class="listBox"><ul>';

                    options.each(function(i, n){
                        var option = $(n),
                            text = option.text(),
                            val = option.val(),
                            cur = "";

                        if(val == selVal){
                            textCurrent = text;
                            cur = " class='current'";
                        }

                        comboList = comboList + '<li'+cur+'><a href="javascript:;" data-val="'+val+'">'+text+'</a></li>';
                    })

                    txtWrap = txtWrap + textCurrent +"</span></span>";
                    comboList = txtWrap + comboList + '</ul></div>';

                    comboList = $(comboList).appendTo(combo);

                    txt = comboList.find(".txt");
                };

            build();

            select.hide();

            combo.on("mouseenter", function(){
                unit.addClass('unitHover');
                combo.addClass('active');
            }).on("mouseleave", function(){
                unit.removeClass('unitHover');
                combo.removeClass('active');
            }).on('click', 'li', function(){
                var li = $(this),
                    text = li.find("a").text(),
                    val = li.find("a").data("val");

                if(val+"" !== select.val()+"") {
                    select.val(val).trigger('change');
                    txt.text(text);
                    li.siblings().removeClass('current').end().addClass('current');
                }
                
                combo.removeClass('active');
            });

            select.on("rebuild", function(){
                build();
            });

            // $(document).on('click', function(e){
            //     if ($(e.target).closest(combo).length < 1) {
            //         combo.removeClass('active');
            //     }
            // })

        })
    },
    J_inputFile: function(){
        if(!this[0])return;

        return this.each(function(){
            var input = $(this),
                unit = input.closest('.J_unit'),
                J_filePickMasker = $('<div id="J_filePickMasker" style="position: absolute; z-index:10" />').appendTo('body'),
                J_filePick = $("#J_filePick"),
                J_filePickOffset = J_filePick.offset(),
                nameShow = input.find(".txt"),
                img = unit.find(".avatar>img"),
                progressBg = input.find(".UploadProgress>.bg");

            input = input.find("input:hidden");

            J_filePickMasker.css({top:J_filePickOffset.top, left:J_filePickOffset.left, width:J_filePick.outerWidth(), height:J_filePick.outerHeight()})

            $.getScript('../js/plupload/plupload.full.min.js', function(){
                var uploader = new plupload.Uploader({
                    runtimes : 'html5,flash,silverlight,html4',
                    browse_button : 'J_filePickMasker',

                    url : 'upload.php', // upload url

                    flash_swf_url : '../js/plupload/Moxie.swf',
                    silverlight_xap_url : '../js/plupload/Moxie.xap',
                    multi_selection : false,
                    filters : {
                        max_file_size : '200kb',
                        mime_types: [
                            {title : "Image files", extensions : "jpg,jpeg,gif,png"}
                        ]
                    },
                    init: {
                        FilesAdded: function(up, files) {
                            var self = this,
                                options = self.getOption();

                            plupload.each(files, function(file) {
                                var img = new mOxie.Image();

                                img.onload = function() {
                                    nameShow.text(img.name).removeClass('empty');

                                    progressBg.show();
                                };
                                img.load(file.getSource());
                            });

                            uploader.start();

                        },

                        UploadProgress: function(up, file) {
                            if(file.percent == 100) {
                                progressBg.fadeOut('400',function(){
                                    progressBg.width(0);
                                });
                            } else {
                                progressBg.width(file.percent +"%");
                            }
                        },

                        FileUploaded: function(up, file, info) {
                            var result = $.parseJSON(info.response)
                            if(result.state == 'ok'){
                                input.val(result.data.path);
                                img.attr("src",result.data.url)
                            }else{
                                console.log(result.data);

                                return false;
                            }
                        },
                        UploadComplete: function(up, file, n){
                            // console.log(file)
                        },
                        Error: function(up, err) {
                            //console.log("\nError #" + err.code + ": " + err.message);
                        }
                    }
                });

                uploader.init();
            })

        })
    }
})

$(function(){
    $('input, textarea').placeholder();

    $(".J_toggleList").hover(function(){
        $(this).toggleClass('active');
    })
    // $(".J_select").J_select();
    $(".J_inputFile").J_inputFile();

    $(".iknow").on("click", function(){
        $(".frontMsg").hide();
    });
    
    if(ie678){
        $(".J_fullBg").each(function(){
            var $this = $(this),
                isUc = $this.is(".ucBg"),
                p = $this.parent().css({"position":"relative","top":0}),
                src = $this.css("backgroundImage").replace( /^url\((['"]?)(.*)\1\)$/,'$2'),
                img = "<img src='"+src+"' width='100%' />",
                resize = function(){
                    var img = $this.find("img").css({"width":"100%","height":"auto","margin-left":0}),
                        h;

                    if(isUc) {
                        h = $this.height();

                    } else {
                        h = Math.max(($(window).height() - p.offset().top), 700);

                        p.height(h);
                    }

                    if(h>img.height()){
                        img.css({"height":h,"width":"auto"});
                        img.css("margin-left",-(img.width() - $this.width())/2);
                    }
                };

            $this.append(img);
            resize();

            $(window).resize(debounce(function(){
                resize();
            },100))
        })
    }

    $(".J_unit").each(function(){
        // console.log($(this)); 
        var SERVER_HOST = $(".SERVER_HOST").val();
        // console.log(SERVER_HOST);
        var SERVER_HOST = SERVER_HOST || 'http://ban.rj-geek.com/';
        var unit = $(this),
            a = unit.find(".J_mod"),
            inputShow = unit.find(".J_input"),
            id = inputShow.data("id"),
            inputName = inputShow.data("name"),
            rel = inputShow.data("rel"),
            focus = inputShow.data("focus"),
            val = inputShow.text(),
            input,
            isSelect = false,
            isFile = false,
            fileVal = 0;
        switch(inputShow.data("type")) {
            case "textarea":
                input = '<textarea class="textarea" id="'+id+'" name="'+inputName+'">'+val+'</textarea>';
            break;

            case "password":
                input = '<input type="password" class="text" id="'+id+'" name="'+inputName+'">';
            break;

            case "select":
                isSelect = true;

                var options = inputShow.data("options");

                input = '<select id="form_city" id="'+id+'" name="'+inputName+'">'

                $.each(options, function(i,n){
                    var selected = val+"" === n.val+"" ? "selected" : "";

                    input = input+'<option value="'+n.val+'" '+selected+'>'+n.name+'</option>';
                });

                input = input + "</select>"
            break;

            case "file":
                isFile = true;
                input = '<div class="inputFile">'+
                    '<input type="hidden" name="avatar" class="upAvatar" value="'+fileVal+'">'+
                    '<div class="txt empty"><font size="1">jpg,gif,png 不超过200kb</font></div>'+
                    '<div class="UploadProgress"><div class="bg"></div></div>'+
                    '<button id="J_filePick" type="button" class="btn btnGreen">浏览文件</button>'+
                '</div>';
            break;

            default:
                input = '<input type="text" class="text" id="'+id+'" name="'+inputName+'" value="'+val+'">';
        }

        a.click(function(){
            // a.closest('.J_form').find("button[type='submit']").removeClass('btnDisabled');
            a.closest('.J_form').find(".subSave").removeClass('btnDisabled');

            input = $(input).insertAfter(inputShow);

            if(isFile) {
                var J_filePickMasker = $('<div id="J_filePickMasker" style="position: absolute; z-index:10" />').appendTo('body'),
                    J_filePick = $("#J_filePick"),
                    J_filePickOffset = J_filePick.offset(),
                    nameShow = input.find(".txt"),
                    img = unit.find(".avatar>img"),
                    progressBg = input.find(".UploadProgress>.bg");

                input = inputShow.find("input:hidden");

                J_filePickMasker.css({top:J_filePickOffset.top, left:J_filePickOffset.left, width:J_filePick.outerWidth(), height:J_filePick.outerHeight()})

                $.getScript('../frontend/js/plupload/plupload.full.min.js', function(){
                    var uploader = new plupload.Uploader({
                        runtimes : 'html5,flash,silverlight,html4',
                        browse_button : 'J_filePickMasker',

                        // url : 'upload.php', // upload url
                        url :  SERVER_HOST +'freeman/upload?token=45a58e00e28c3831fe04fdf8aae70fd4&type=photo',
                        flash_swf_url : '../frontend/js/plupload/Moxie.swf',
                        silverlight_xap_url : '../frontend/js/plupload/Moxie.xap',
                        multi_selection : false,
                        filters : {
                            max_file_size : '5mb',
                            mime_types: [
                                {title : "Image files", extensions : "*"}
                            ]
                        },
                        init: {
                            FilesAdded: function(up, files) {
                                var self = this,
                                    options = self.getOption();

                                plupload.each(files, function(file) {
                                    var img = new mOxie.Image();

                                    img.onload = function() {
                                        nameShow.text(img.name).removeClass('empty');

                                        progressBg.show();
                                    };
                                    img.load(file.getSource());
                                });

                                uploader.start();

                            },

                            UploadProgress: function(up, file) {
                                if(file.percent == 100) {
                                    progressBg.fadeOut('400',function(){
                                        progressBg.width(0);
                                    });
                                } else {
                                    progressBg.width(file.percent +"%");
                                }
                            },

                            FileUploaded: function(up, file, info) {
                                var result = $.parseJSON(info.response)
                                if(result.err == 0){
                                    $(".upAvatar").val(result.src);
                                    $(".avatar img").attr("src", result.filePath);
                                }else{
                                    alert(result.msg);

                                    return false;
                                }
                                // console.log(result);
                                // if(result.state == 'ok'){
                                //     input.val(result.data.path);
                                //     img.attr("src",result.data.url)
                                //     // console.log(result.data.path);
                                //     // console.log(result.data.url);
                                // }else{
                                //     console.log(result.data);

                                //     return false;
                                // }
                            },
                            UploadComplete: function(up, file, n){
                                // console.log(file)
                                // console.log(file);
                            },
                            Error: function(up, err) {
                                //console.log("\nError #" + err.code + ": " + err.message);
                            }
                        }
                    });

                    uploader.init();
                })
            } else if(isSelect) {
                input.J_select();
                if(rel) {
                    input.change(function(){
                        $.getJSON(inputShow.data("relurl"), {"val": input.val()}, function(data){
                            if(data.success) {
                                var options = ""
                                $.each(data.options, function(i, n){
                                    var selected = n.selected ? "selected" : "";

                                    options = options+'<option value="'+n.val+'" '+selected+'>'+n.name+'</option>';
                                })

                                $(rel).find("select").html(options).trigger('rebuild');

                            } else {
                                //console.log("Error: " + data.message);
                            }
                        })
                    });            
                }
            } else {
                if(focus !== false) {
                    var len = val.length;
                    input = input.focus()[0];

                    if (document.selection) {
                        var sel = input.createTextRange();

                        sel.moveStart('character',len);
                        sel.collapse();
                        sel.select();
                    } else if (typeof input.selectionStart == 'number' && typeof input.selectionEnd == 'number') {
                        input.selectionStart = input.selectionEnd = len;
                    }
                }
            }

            a.hide();
            inputShow.hide();

            $(rel).show().find(".J_mod").trigger("click");

            return false;
        })
    })



})