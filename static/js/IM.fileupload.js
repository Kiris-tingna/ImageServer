'use strict';

$(function () {
    var dropbox = $('#upload');// drop area 
    var album = $('#PicAlbum');
    var detail = $('#PicDetail');
    var lastModifyId = 0;

    var _template = '<div class="IM-item col-sm-6 col-md-4">\
        <div class="thumbnail">\
            <img>\
            <div class="caption">\
                <p class="IM-item-head"></p>\
            </div>\
            <div class="progress">\
                <div class="progress-bar progress-bar-info progress-bar-striped" \
                role="progressbar">\
            </div>\
        </div>';

    // 预览图片回调函数 
    var preview = function (index, file) {
        // to last+1, last+2,....
        var uuid =  lastModifyId + index + 1;

        var _item = $(_template);
        var _image = $('img', _item);
        var _reader = new FileReader();

        // e.target.result holds the DataURL which
        // can be used as a source of the image:
        _reader.onload = function (e){    
            _image.attr('src',e.target.result);
        };
        
        // Reading the file as a DataURL. When finished,
        // this will trigger the onload function above:
        _reader.readAsDataURL(file);

        _item.attr('data-id', uuid);
        _item.find('.IM-item-head').text(file.name);
        // add pic item
        _item.appendTo(album);

        // bind file data
        $.data(file, _item);
    }
    /**
     * start drop
     *  to do allert service
     *
     * 
     * @return [拖拽上传]
     */
    dropbox.filedrop({
        // server address
        url: './index/upload',

        // The name of the $_FILES 
        paramname:'images',
        // The max size of images
        maxfiles: 5,
        // The max size og single pic (MB)
        maxfilesize: 4,
        
        // handle error
        error: function(err, file) {
            switch(err) {
                case 'BrowserNotSupported':
                    showMessage('Your browser does not support HTML5 file uploads!');
                    break;
                case 'TooManyFiles':
                    alert('Too many files! Please select 5 at most! (configurable)');
                    break;
                case 'FileTooLarge':
                    alert(file.name+' is too large! Please upload files up to 2mb (configurable).');
                    break;
                default:
                    break;
            }
        },
        // Called before each upload is started
        beforeEach: function(file){
            if(!file.type.match(/^image\//)){
                alert('Only images are allowed!');
                
                // Returning false will cause the
                // file to be rejected
                return false;
            }
            
            // if(/\s/.test(file.name)) {
            //     alert('名称中不能包含空格');
            //     return false;
            // }
        },
        afterAll: function () {
            // 更新last
            $('.IM-item').each(function () {
                if(lastModifyId < $(this).data('id'))
                    lastModifyId = $(this).data('id');
            });
            // console.log(lastModifyId);
        },
        uploadFinished:function(index, file, response, time){
            // console.log(response);
            // console.log(index);
            var uuid = lastModifyId + index + 1;
            var $target = $('.IM-item[data-id='+uuid+']');
            // remove progress animation
            $target.find('.progress-bar').removeClass('active');
            $target.data('url', response.data);
            $target.on('click', function (e) {
                var url = $(this).data('url');
                detail.find('#p1').val(url);
                detail.find('#p2').val("<a href='" + url + "'>"+"</a>");
                detail.find('#p3').val("<img src='" + url + "'/>");
                detail.find('#p4').val("![](" + url + ")");
                detail.modal('show');
            });
        },
        // 生成预览图
        uploadStarted:function(index, file, length){
            preview(index, file);
        },
        
        progressUpdated: function(index, file, progress) {
            $.data(file).find('.progress-bar').addClass('active').width(progress+'%');
        }
         
    });

    $('#Url-btn').on('click', function (){
        var _url = $('#Url-input').val();
        $.ajax({
            url: './index/uploadUrl',
            method: 'POST',
            dataType: 'json',
            data: {'url': _url}
        }).done(function (data) {
            var _area = $('#Url-content .IM-preview');
            $('.IM-alert').html(data.message);
            _area.empty();
            _area.append($('<img class="img-responsive">').attr('src', data.data));
        })
    })
});