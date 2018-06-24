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
        <span>手机号码</span>
        <p class="input-p">
            <input type="hidden" name="step" id="step" value="first" />
            <input type="hidden" name="PatchCode" id="PatchCode" value="" />
            <input type="hidden" name="taskno" id="taskno" value="" />
            <input type="text" name="telephone" id="telephone" value="<?php echo ($member["telephone"]); ?>" placeholder="请输入手机号码" />
        </p>
    </div>
    <div>
        <span>服务密码</span>
        <p class="input-p">
            <input type="password" name="servicepwd" id="servicepwd" value="" placeholder="请输入服务密码" />
        </p>
    </div>
    <div id="smsbox" style="display: none;">
        <span>短信验证</span>
        <p class="input-p">
            <input type="number" name="smscode" id="smscode" value="" placeholder="请输入短信验证码" />
        </p>
    </div>
    <div id="namebox" style="display: none;">
        <span>姓名</span>
        <p class="input-p">
            <input type="text" name="username" id="username" value="<?php echo ($member["username"]); ?>" placeholder="请输入姓名" />
        </p>
    </div>

    <div id="idcardbox" style="display: none;">
        <span>身份证号</span>
        <p class="input-p">
            <input type="text" name="idcard" id="idcard" value="<?php echo ($member["idcard"]); ?>" placeholder="请输入身份证号" />
        </p>
    </div>

</section>
<div class="vertical-top">
    提示
</div>
<section id="tip" class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;padding: 0.2rem;font-size: 0.12rem;color: #999;">

</section>
<div class="vertical-top">
    常见问题
</div>
<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
    <div>
        <a class="shibai" style="color: #E6BC85;">授权失败的常见原因？</a>
    </div>
    <div>
        <a class="huode" style="color: #E6BC85;">如何获得服务密码？</a>
    </div>
</section>
<div class="vertical-top">
    点击“同意授权”按钮表示接受如下内容
</div>
<section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;padding: 0.2rem;font-size: 0.12rem;color: #999;">
    1、 授权云蜂获取以下信息包括但不限于姓名、身份证号、手机号码、通话记录，用于生成个人信息报告；2、 你的好友可以查看你的手机号码使用时长、互通联系人数量、通话次数、通话时长以及短信条数、话费缴纳情况，以甄别出借风险；3、 当你发生违约或其他影响信用的不良行为时，云查宝有权利利用相关信息向你的联系人发送提醒消息，如有需要还会向有关部门提供可能联系到你的电话号码、地址等信息，用于追究法律责任；
</section>

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
        同意授权
    </button>
</div>

<style type="text/css">
    .Mask {
        width: 100%;
        background: rgba(0, 0, 0, 0.8);
        position: fixed;
        top: 0;
        display: none;
        z-index: 99;
        font-size: 0.13rem;
    }

    .mask-shibai,.mask-huode{
        color: #fff;
        padding: 0.4rem 0.2rem 0;
        display: none;
    }

    .mask-shibai p,.mask-huode p {
        margin-bottom: 0.12rem;
    }

    .know-box {
        text-align: center;
    }

    .know {
        width: 2.5rem;
        height: 0.5rem;
        margin-top: 0.2rem;
        border-radius: 0.25rem;
        border: 0;
        background: #FFFFFF;
        font-size: 0.16rem;
    }
</style>
<div class="Mask">
    <div class="mask-shibai">
        <p style="font-size: 0.16rem;">授权失败的常见原因？</p>
        <p>1、全部173、大部分177、大部分170号码段，企业账号携号转网用户，不能正常授权成功; </p>
        <p>2、电信（吉林、陕西、湖南、浙江、重庆、广西、云南）这8个省份的号码必须实名制才可以; </p>
        <p>3、授权次数过多导致达到上限，需要等待或者更新手机号再次尝试;</p>
        <p>4、短信验证码超时没有输入，需要刷新当前页面或者返回上一页面再次尝试;</p>
    </div>
    <div class="mask-huode">
        <p style="font-size: 0.16rem;">如何获取服务密码?？</p>
        <p>移动用户</p>
        <p>方法一：</p>
        <p>手机拨打 10086，转“人工服务”; </p>
        <p>方法二：</p>
        <p>访问 www.10086.cn，登入，“忘记密码”查询;</p>
        <p>联通用户</p>
        <p>方法一：</p>
        <p>手机拨打 10010，转“人工服务”;</p>
        <p>方法二：</p>
        <p>访问 wap.10010.cn，登入，“忘记密码”查询;</p>
        <p>电信用户</p>
        <p>方法一：</p>
        <p>手机拨打 10000，转“人工服务”;</p>
        <p>方法二：</p>
        <p>访问 wapzt.189.cn，登入，“忘记密码”查询;</p>
    </div>
    <div class="know-box">
        <button class="know">
            知道了
        </button>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var h = $(window).height();
        $(".Mask").css(
                "height", h
        )
        //失败的按钮
        $(".shibai").click(function() {
            $(".Mask").slideToggle(0);
        })
        $(".shibai").click(function() {
            $(".mask-shibai").slideToggle(0);
        })
        //获得的按钮
        $(".huode").click(function() {
            $(".Mask").slideToggle(0);
        })
        $(".huode").click(function() {
            $(".mask-huode").slideToggle(0);
        })
        $(".know").click(function() {
            $(".Mask").slideToggle(0);
        })
        $(".know").click(function() {
            $(".mask-huode").css(
                    "display","none"
            );
        })
        $(".know").click(function() {
            $(".mask-shibai").css(
                    "display","none"
            );
        })
    })
</script>

<script src="/Public/newcss/js/myfunc-1.0.0.js" ></script>
<script type="text/javascript">

    function committmobile(){

        var telephone=$("#telephone").val();
        var servicepwd=$("#servicepwd").val();
        var smscode=$("#smscode").val();
        var step= $("#step").val();
        var PatchCode= $("#PatchCode").val();
        var taskno= $("#taskno").val();
        var username= $("#username").val();
        var idcard= $("#idcard").val();

        if($.trim(telephone)==null||$.trim(telephone)==""){
            yjfunc.mytoast("请输入电话号码");return;
        }
        if($.trim(servicepwd)==null||$.trim(servicepwd)==""){
            yjfunc.mytoast("请输入服务密码");return;
        }

        $("#shouquan").text("获取中，请稍后");
        $("#shouquan").attr('disabled','disabled');

        $.ajax({
            url:"/Member/subtmobile.html",
            data:{telephone:telephone,servicepwd:servicepwd,smscode:smscode,step:step,PatchCode:PatchCode,taskno:taskno,username:username,idcard:idcard},
            type:"POST",
            success: function (data) {
                switch (parseInt(data.status)){
                    case 0:
                        yjfunc.myconfirm(data.info);
                        break;
                    case 1:
                        getdetail(data.taskno,telephone,servicepwd);
                        break;
                    case 2:
                        yjfunc.mytoast("授权成功");
                        setTimeout(function () {
                            window.location.href="/";
                        },1000);
                        break;

                }


            }
        })

    }

    function getdetail(taskno,telephone,servicepwd){
        $.ajax({
            url:"/Member/getdatadetail.html",
            data:{taskno:taskno,telephone:telephone,servicepwd:servicepwd},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    var restep=data.step;
                    $("#tip").html(data.info);
                    $("#step").val(restep);
                    $("#taskno").val(taskno);
                    $("#PatchCode").val(data.PatchCode);
                    yjfunc.myconfirm(data.info);
                    $("#shouquan").text("再次授权");
                    $("#shouquan").removeAttr('disabled');
                    switch(restep){
                        case "carrier_1":
                            $("#smscode").val('');
                            $("#smsbox").hide();
                            break;
                        case "carrier_4":
                            $("#smsbox").show();
                            break;
                        case "carrier_5":
                            $("#smscode").val('');
                            $("#smsbox").show();
                            $("#smscode").val("");
                            break;
                        case "carrier_6":
                            $("#smscode").val('');
                            $("#smsbox").show();
                            $("#smscode").val("");
                            break;
                        case "carrier_10":
                            $("#smsbox").show();
                            $("#smscode").val("");
                            break;
                        case "carrier_11":
                            $("#smscode").val('');
                            $("#smsbox").show();
                            $("#smscode").val("");
                            break;
                        case "carrier_16":
                            $("#namebox").show();
                            $("#idcardbox").show();
                            break;
                        case "carrier_17":
                            $("#namebox").show();
                            $("#idcardbox").show();
                            break;
                        case "general_0":
                            yjfunc.mytoast("授权成功");
                            setTimeout(function () {
                                window.location.href="/";
                            },1000);
                            break;
                    }
                }else{
                    setTimeout(function () {
                        getdetail(taskno,telephone,servicepwd);
                    },3000);
                }

            }

        })

    }


</script>

</body>
</html>