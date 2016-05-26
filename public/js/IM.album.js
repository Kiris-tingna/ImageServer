'use strict';
$(function () {
    var IA = $('.IM-album-section .flexslider .slides');
    // var ia_template = '';

    $.ajax({
        url: './show-album-all',
        method: 'GET',
        dataType: 'json',
        data: {}
    })
    .done(function (data) {
        var rs = data.data;
        // console.log(rs);
        $.each(rs, function (index, value){
            var _header = $('.IM-album-header');
            var _desc = $('.IM-album-desc');
            
            _header.text(value.name);
            _desc.text(value.description);

            // 插入封面
            // _img.find('img').attr('src', value.albums[0]['origin_url']);
            // IA.append(_img);
            
            value.albums.forEach(function (i){
                IA.append('<li><img src="'+i['origin_url']+'"></li>');
            });
        });
        // 开启幻灯
        $('.IM-album-section .flexslider').flexslider({
            animation: "fade",
            animationLoop: false,
            itemWidth: 500,
            itemMargin: 0,
            pausePlay: false
        });
    })
    .fail(function (err) {
        console.log(err);
    })
})