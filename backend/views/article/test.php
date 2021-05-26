<?php
?>

<script type="text/javascript" src="/static/js/jquery.min.js"></script>


<html>
<body>



<div id="ad_line" style="text-align: center;"></div>
<h2>图片在线webp/png/jpeg格式转换工具</h2>
<div class="fli">
    <span>选择图片：</span><input type="file" id="inputimg">
    <div class="sdiv">
        <span>选择格式：</span>
        <select id="myselect">
            <option value="1">webp格式</option>
            <option value="2">jpeg格式</option>
            <option value="3">png格式</option>
        </select>
    </div>

    <button id="start">开始转换</button>
    <button id="rmfile">替换file</button>
</div>
<div class="fli">
    <p>预览：</p>
    <img id="imgShow" src="" alt="">
</div>

<div>

    <form id="w0" class="form-horizontal" action="/article/test" method="post" enctype="multipart/form-data">

        <div>
            <input type="file" name="upfile" id="upfile1" />
        </div>
        <input type="submit" value="Submit" />
    </form>
</div>

<script type="text/javascript">
    $(function(){
        // alert('aaaa');
        $('#rmfile').click(function(){
            console.log('333333');
            var imgsrc = $('#imgShow').attr('src');
            // console.log($('#imgShow').attr('src'));
            var newfile = '<input type="hidden" value="'+imgsrc+'" name="upfile1" />';
            $('#upfile1').parent().append(newfile);
            $('#upfile1').remove();

            var formData = new FormData();   //这里连带form里的其他参数也一起提交了,如果不需要提交其他参数可以直接FormData无参数的构造函数

            //convertBase64UrlToBlob函数是将base64编码转换为Blob
            formData.append("ImgeData", convertBase64UrlToBlob(imgsrc));  //append函数的第一个参数是后台获取数据的参数名,和html标签的input的name属性功能相同
            formData.append("id","123");//附加参数
            var url = "/article/upload";

            //ajax 提交form
            $.ajax({
                url : url,
                type : "POST",
                data : formData,
                dataType:"json",
                processData : false,         // 重要！！告诉jQuery不要去处理发送的数据
                contentType : false,        // 重要！！告诉jQuery不要去设置Content-Type请求头
                cache: false,        //关闭缓存
                success: function(data) {
                    console.log(data)
                    console.error(data);
                }
            });

        });
    });

    /**
     * https://blog.csdn.net/H_Elie/article/details/115471125
     * https://www.jianshu.com/p/e45984306df4
     * 将以base64的图片url数据转换为Blob
     * @param urlData
     * 用url方式表示的base64图片数据
     */
    function convertBase64UrlToBlob(urlData){
        var bytes = window.atob(urlData.split(',')[1]);        //如果包含data:image/png;base64,串，需要去掉url的头，并转换为byte
        //var bytes=window.atob(urlData);        //去掉url的头，并转换为byte
        //处理异常,将ascii码小于0的转换为大于0
        var ab = new ArrayBuffer(bytes.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < bytes.length; i++) {
            ia[i] = bytes.charCodeAt(i);
        }

        return new Blob( [ab] , {type : 'image/png'});
    }
</script>


</body>
</html>
<script type="text/javascript" >
    /*事件*/
    document.getElementById('start').addEventListener('click', function(){
        getImg(function(image){
            var can=imgToCanvas(image),
                imgshow=document.getElementById("imgShow");
            imgshow.setAttribute('src',canvasToImg(can));
        });
    });
    // 把image 转换为 canvas对象
    function imgToCanvas(image) {
        var canvas = document.createElement("canvas");
        canvas.width = image.width;
        canvas.height = image.height;
        canvas.getContext("2d").drawImage(image, 0, 0);
        return canvas;
    }
    //canvas转换为image
    function canvasToImg(canvas) {
        var src = canvas.toDataURL("image/webp");
        return src;
    }
    //获取图片信息
    function getImg(fn){
        var imgFile = new FileReader();
        try{
            imgFile.onload = function(e) {
                var image = new Image();
                image.src= e.target.result; //base64数据
                image.onload=function(){
                    fn(image);
                }
            }
            imgFile.readAsDataURL(document.getElementById('inputimg').files[0]);
        }catch(e){
            console.log("请上传图片！"+e);
        }
    }
</script>

<style type="text/css">
    *{
        outline: none;
    }
    .center{
        min-width:1100px;
    }
    .center h2{
        text-align: center;height: 60px;line-height: 60px;border-bottom: 1px solid #ddd;
    }
    .fli{
        color:#666;font-size: 16px;margin: 10px auto;width: 1100px;
    }
    .fli .name{
        font-size: 16px;margin: 10px auto;color:#1FBCB6;
    }
    .fli img{
        max-width: 400px;
    }
    button{
        border: 0;background: #1FBCB6;color:#fff;padding: 8px 15px;cursor: pointer;
    }
    textarea{
        width: 100%;max-width: 100%;max-height: 500px;
    }
    button:hover{
        background: #1B6D85;
    }

    .sdiv{
        margin: 20px auto;

    }
    select{
        height: 26px;line-height: 26px;border: 1px solid #888;
    }
</style
