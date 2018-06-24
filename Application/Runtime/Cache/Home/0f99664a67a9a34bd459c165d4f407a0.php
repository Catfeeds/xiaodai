<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo ($title); ?></title>
    <meta charset="UTF-8">
<meta name="renderer" content="webkit">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--百度禁止转码 -->
<title>登录</title>
<link rel="stylesheet" type="text/css" href="/Public/newcss1/css/base.css" />
<script src="/Public/newcss1/js/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/mobileRsize.js" type="text/javascript" charset="utf-8"></script>
<script src="/Public/newcss1/js/myfunc-1.0.0.js" type="text/javascript" charset="utf-8"></script>
    <style type="text/css">
        input::-webkit-input-placeholder {
            color: #999;
        }

        input::-moz-placeholder {
            /* Mozilla Firefox 19+ */
            color: #999;
        }

        input:-moz-placeholder {
            /* Mozilla Firefox 4 to 18 */
            color: #999;
        }

        input:-ms-input-placeholder {
            /* Internet Explorer 10-11 */
            color: #999;
        }

        .input-box>div {
            padding: 0.14rem 0.08rem;
            width: 94%;
            margin: 0 auto;
            box-sizing: border-box;
            position: relative;
            overflow: hidden;
        }

        .vertical-top {
            margin: 0.12rem 0.12rem 0;
            padding-left: 0.12rem;
            position: relative;
            font-weight: bold;
            color: #999;
            font-size: 0.14rem;
        }

        .vertical-top:before {
            content: "";
            display: block;
            background: #E6BC85;
            width: 0.04rem;
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
        }

        .input-box>div:after {
            left: 0;
            content: "";
            position: absolute;
            bottom: 0;
            border-bottom: 1px solid #CCCCCC;
            width: 100%;
            -webkit-transform: scaley(.3);
            transform: scaley(.3);
        }

        .input-box>div:last-child:after {
            border: 0;
        }

        .input-box span {
            display: inline-block;
            text-align: center;
            color: #000;
            float: left;
            width: 0.9rem;
            text-align: left;
        }

        .input-box .input-p {
            margin-left: 0.9rem;
        }

        .input-box input,
        .input-box select {
            border: 0;
            width: 100%;
        }
    </style>
</head>

<body style="background: #f5f5f5;">

<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
    <div>
        <span>淘宝认证</span>
        <p class="input-p">
            <input type="hidden" name="step" id="step" value="first" />
            <input type="hidden" name="PatchCode" id="PatchCode" value="" />
            <input type="hidden" name="taskno" id="taskno" value="" />
            <img src="" alt="">
        </p>
    </div>
    <!--<div id="smsbox" style="display: none;">
        <span>短信验证</span>
        <p class="input-p">
            <input type="number" name="smscode" id="smscode" value="" placeholder="请输入短信验证码" />
        </p>
    </div>-->
</section>
<div class="vertical-top">
    提示
</div>
<section id="tip" class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;padding: 0.2rem;font-size: 0.12rem;color: #999;">

</section>
<div  id='a' style='margin-top:1rem; text-align:center;display:none '>

    <img id="img" src="" alt="二维码获取区域" style="width: 1.8rem;">


</div>

<style type="text/css">
    .bot-btn {
        padding: 0.1rem 0.12rem;
        background: #fff;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        box-sizing: border-box;
        border-radius: 0.25rem 0.25rem 0 0;
        box-shadow: 0px 0px 8px 0px rgba(0, 0, 0, 0.1);
    }

    .bot-btn button {
        background: #e6bc85;
        height: 0.4rem;
        border-radius: 0.25rem;
        width: 100%;
        display: block;
        color: #fff;
        font-size: 0.2rem;
        line-height: 0.4rem;
        text-align: center;
        font-weight: bold;
        border: 0;
    }

    .footpadding {
        padding-top: 0.5rem;
    }
</style>
<div class="footpadding"></div>
<div class="bot-btn">
    <button id="shouquan" onclick="committmobile()">
        点击获取二维码
    </button>
</div>



<script src="/Public/newcss/js/myfunc-1.0.0.js" ></script>
<script type="text/javascript">

    function committmobile(){
        var step =$('#step').val();
        var PatchCode= $("#PatchCode").val();
       /* var smscode=$("#smscode").val();*/
        var taskno=$("#taskno").val();

        $("#shouquan").text("获取中，请稍后");
        $("#shouquan").attr('disabled','disabled');
        $.ajax({
            url:"/Member/subtaobao.html",
            data:{step:step,PatchCode:PatchCode,taskno:taskno},
            type:"POST",
            success: function (data) {

                switch (parseInt(data.status)){

                    case 0:
                        yjfunc.myconfirm(data.info);
                        break;
                    case 1:

                        getdetail(data.taskno);
                        break;
                    case 2:
                        yjfunc.mytoast("授权成功");
                        setTimeout(function () {
                            window.location.href="/Index/menu.html";
                        },1000);
                        break;
                        break;
                }


            }
        })

    }

    function getdetail(taskno){
        $.ajax({
            url:"/Member/gettaobaodata.html",
            data:{taskno:taskno},
            type:"POST",
            success: function (data) {
                console.log(data);
                if(data.status==1){
                    var restep=data.step;
                    $("#tip").html(data.info);
                    $("#step").val(restep);
                    $("#taskno").val(taskno);
                    $("#PatchCode").val(data.PatchCode);
                    yjfunc.myconfirm(data.info);

                    switch(restep){
                        case "taobao_6":
						    $("#a").show();
                            $('#img').attr('src',data.img);
                            $("#shouquan").text("再次授权");
                            $("#shouquan").removeAttr('disabled');
                            getdetail(taskno);
                            yjfunc.mytoast(data.info);
                            break;

                        case "taobao_13":
                            $("#a").show();
                            $('#img').attr('src',data.img);
                            $("#shouquan").text("再次授权");
                            $("#shouquan").removeAttr('disabled');
                            getdetail(taskno);
                            yjfunc.mytoast(data.info);
                            break;
                        case "taobao_7":
                            $("#a").show();
                            yjfunc.mytoast(data.info);
                            break;
                        case "taobao_8":
                            $("#a").show();
                            yjfunc.mytoast(data.info);
                            break;
                        case "general_0":

                            $("#a").show();
                            yjfunc.mytoast("授权成功");
                            setTimeout(function () {
                                window.location.href="/Index/menu.html";
								
						
                            },1000);
                            break;
                    }
                }else{
                    setTimeout(function () {
                        getdetail(taskno);
                    },3000);
                }

            }

        })

    }


</script>

</body>
</html>