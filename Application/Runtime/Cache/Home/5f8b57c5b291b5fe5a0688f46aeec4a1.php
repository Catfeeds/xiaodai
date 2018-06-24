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


<?php if(!empty($contacts)): if(is_array($contacts)): $k = 0; $__LIST__ = $contacts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="vertical-top">
            紧急联系人<?php echo ($k); ?>
            <input type="hidden" name="seq[]" value="<?php echo ($k); ?>"/>
        </div>
        <section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
            <div>
                <span>选择关系<?php echo ($vo['relationship']); ?></span>
                <p class="input-p">
                    <select name="relationship[]" required="" style="color: #999;background: #fff;">
                        <option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                        <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                        <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                        <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                        <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                        <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                        <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                        <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                    </select>
                </p>
            </div>
            <div>
                <span>联系人姓名</span>
                <p class="input-p">
                    <input type="text" name="username[]" value="<?php echo ($vo["username"]); ?>" placeholder="请输入联系人姓名" />
                </p>
            </div>
            <div>
                <span>联系人电话</span>
                <p class="input-p">
                    <input type="text" name="telephone[]" value="<?php echo ($vo["telephone"]); ?>" placeholder="请输入联系人电话" />
                </p>
            </div>
        </section><?php endforeach; endif; else: echo "" ;endif; ?>
    <?php else: ?>
    <div class="vertical-top">
        紧急联系人1
        <input type="hidden" name="seq[]" value="1"/>
    </div>
    <section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
        <div>
            <span>选择关系</span>
            <p class="input-p">
                <select name="relationship[]" required="" style="color: #999;background: #fff;">
                    <option class="tab" value="" style="color: #999 !important;" disabled selected>请选择你们的关系</option>
						<option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                        <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                        <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                        <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                        <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                        <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                        <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                        <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                </select>
            </p>
        </div>
        <div>
            <span>联系人姓名</span>
            <p class="input-p">
                <input type="text" name="username[]" value="" placeholder="请输入联系人姓名" />
            </p>
        </div>
        <div>
            <span>联系人电话</span>
            <p class="input-p">
                <input type="text" name="telephone[]" value="" placeholder="请输入联系人电话" />
            </p>
        </div>
    </section>

    <div class="vertical-top">
        紧急联系人2
        <input type="hidden" name="seq[]" value="2"/>
    </div>
    <section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
        <div>
            <span>选择关系</span>
            <p class="input-p">
                <select name="relationship[]" required="" style="color: #999;background: #fff;">
                    <option class="tab" value="" style="color: #999 !important;" disabled selected>请选择你们的关系</option>
						<option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                        <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                        <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                        <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                        <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                        <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                        <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                        <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                </select>
            </p>
        </div>
        <div>
            <span>联系人姓名</span>
            <p class="input-p">
                <input type="text" name="username[]" value="" placeholder="请输入联系人姓名" />
            </p>
        </div>
        <div>
            <span>联系人电话</span>
            <p class="input-p">
                <input type="text" name="telephone[]" value="" placeholder="请输入联系人电话" />
            </p>
        </div>
    </section><?php endif; ?>

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
        border:0;
    }

    .footpadding {
        padding-top: 0.5rem;
    }
</style>
<div class="footpadding"></div>
<div class="bot-btn">
    <button onclick="commitcontact()">
        完成
    </button>
</div>

<script type="text/javascript">
    $("select").click(function() {
        $(this).css(
                "color", "#000"
        );
    });
</script>
<script src="/Public/newcss/js/myfunc-1.0.0.js" ></script>


<script type="text/javascript">

    function commitcontact(){
        var seq="";
        var username="";
        var relationship="";
        var telephone="";
        var err=0;
        $("input[name='seq[]']").each(function (index) {
            seq+=$(this).val()+",";
        });
        $("select[name='relationship[]']").each(function (index) {
            if($.trim($(this).val())==null||$.trim($(this).val())==""){
                err=1;
            }
            relationship+=$(this).val()+",";
        });
        if(err==1){
            yjfunc.mytoast("请选择联系人关系");return;
        }


        $("input[name='username[]']").each(function (index) {
            if($.trim($(this).val())==null||$.trim($(this).val())==""){
                err=1;
            }
            username+=$(this).val()+",";
        });
        if(err==1){
            yjfunc.mytoast("请输入联系人姓名");return;
        }

        $("input[name='telephone[]']").each(function (index) {
            if($.trim($(this).val())==null||$.trim($(this).val())==""){
                err=1;
            }
            telephone+=$(this).val()+",";
        });
        if(err==1){
            yjfunc.mytoast("请输入联系人电话号码");return;
        }



        $.ajax({
            url:"/Member/subcontact.html",
            data:{username:username,seq:seq,telephone:telephone,relationship:relationship},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    yjfunc.myconfirm(data.info,["ok"], function (e) {
                        if(e==0){
                            window.location.href="/Index/menu.html";
                        }
                    })
                }else{
                    yjfunc.myconfirm(data.info);
                }
            }
        })

    }



</script>

</body>
</html>