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
    <div class="vertical-top">
        工作信息
        <input type="hidden" name="seq[]" value="2"/>
    </div>
    <section class="input-box" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">

        <div>
            <span>从事行业:</span>
            <p class="input-p">
                <input type="text" id='trade' name="trade" value="<?php echo ($db['trade']); ?>" placeholder="如：餐饮业" />
            </p>
        </div>
        <div>
            <span>工作职位:</span>
            <p class="input-p">
                <input type="text" id='work'  name="work" value="<?php echo ($db['work']); ?>" placeholder="如：服务员/店长/高级美发师" />
            </p>
        </div>


        <div>
            <span>单位名称:</span>
            <p class="input-p">
                <input type="text"  id='name' name="name" value="<?php echo ($db['name']); ?>" placeholder="如：北京xx餐饮管理有限公司" />
            </p>
        </div>

        <div>
            <span>单位电话:</span>
            <p class="input-p">
                <input type="text" id='telephone'  name="telephone" value="<?php echo ($db['telephone']); ?>" placeholder="如：单位电话" />
            </p>
        </div>
        <div>
            <span>单位详细地址:</span>
            <p class="input-p">
                <input type="text" id='address' name="address" value="<?php echo ($db['address']); ?>" placeholder="如：xx街32号1-1602室" />
            </p>
        </div>
        <div>
            <span>借款用途:</span>
            <p class="input-p">
                <select name='' required="" style="color: #999;background: #fff;" id="use">
                    <option class="tab" value="" style="color: #999 !important;" disabled selected>请选择用途</option>
                    <option <?php if($db['use'] == 1): ?>selected<?php endif; ?> value="1">租房</option>
                    <option <?php if($db['use'] == 2): ?>selected<?php endif; ?> value="2">手机数码</option>
                    <option <?php if($db['use'] == 3): ?>selected<?php endif; ?> value="3">健康医疗</option>
                    <option <?php if($db['use'] == 4): ?>selected<?php endif; ?> value="4">旅游</option>
                    <option <?php if($db['use'] == 5): ?>selected<?php endif; ?> value="5">家具家居</option>
                    <option <?php if($db['use'] == 6): ?>selected<?php endif; ?> value="6">其他</option>
                </select>
            </p>
        </div>

        <div>
            <span>婚姻情况:</span>
            <p class="input-p">
                <input type="radio" id='marriage1' style="width:35px;" name="marriage" value="1" <?php if($db['marriage'] == 1): ?>checked<?php endif; ?> />已婚
                <input type="radio" id='marriage2' style="width:35px;" name="marriage" value="0" <?php if($db['marriage'] == 0): ?>checked<?php endif; ?> />未婚
            </p>
        </div>

            <div>
                <p> 请上传工作凭证：如工牌，社保卡……</p>

            </div>
            <style type="text/css">
                .zj img{
                    width: 100%;
                }
            </style>
            <section class="input-box zj" style="margin: 0.12rem;background: #fff;border-radius:0.2rem ;font-size: 0.14rem;">
            <input type="file" onchange="getnewUrl(this.files,'workimg');" style="display: none;" class="uploadpic"  />
            <p onclick="$(this).prev('.uploadpic').click();"  class="workimg" style="height: 2.1rem;position: relative;background: url(/Public/newcss/img/jia.png) no-repeat center / 0.5rem 0.5rem;">
                <?php if(!empty($db["workimg"])): ?><img class="idimg"  src="<?php echo ($db["workimg"]); ?>"><?php endif; ?>
            </p>
            <input type="hidden" name="workimg" value="<?php echo ($db["workimg"]); ?>" />
            </section>

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
    <canvas id="myCanvas" style="display: none;"></canvas>
    <img id="agoimg" style="display:none;"/>
</body>
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

        var trade=$("#trade").val();
        var work=$("#work").val();
        var name=$("#name").val();
        var telephone=$("#telephone").val();
        var address=$("#address").val();
        var use=$("#use").val();
        if($("input[name='workimg']").val()){
            
            var workimg=$("input[name='workimg']").val();
        }else{
            var workimg='';
        }

        var marriage=$("input[type='radio']:checked").val();



        if($.trim(trade)==""||$.trim(trade)==null){
            yjfunc.mytoast("请输入从事行业");return;
        }
        if($.trim(work)==""||$.trim(work)==null){
            yjfunc.mytoast("请输入工作单位");return;
        }

        if($.trim(name)==""||$.trim(name)==null){
            yjfunc.mytoast("请输入单位名称");return;
        }

        if($.trim(telephone)==""||$.trim(telephone)==null){
            yjfunc.mytoast("请输入单位电话");return;
        }
        if($.trim(address)==""||$.trim(address)==null){
            yjfunc.mytoast("请输入单位详细地址");return;
        }
        if($.trim(use)==""||$.trim(use)==null){
            yjfunc.mytoast("请选择借款用途");return;
        }
        if($.trim(marriage)==""||$.trim(marriage)==null){
            yjfunc.mytoast("请选择婚姻状况");return;
        }




        $.ajax({
            url:"/Member/subwork.html",
            data:{trade:trade,work:work,name:name,telephone:telephone,marriage:marriage,address:address,use:use,workimg:workimg},
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

<script>

    function getnewUrl(fil,classes) {
        if(fil.length<=0){
            return;
        }
        var Cnv = document.getElementById('myCanvas');
        var Cntx = Cnv.getContext('2d');//获取2d编辑容器
        var imgss =   new Image();
        var agoimg=document.getElementById("agoimg");
        $("."+classes).html("<img style='width: 100%; height: 100%;' src=\"/Public/Home/images/loading.gif\"/>");
//        alert(fil.length);
        for (var intI = 0; intI < fil.length; intI++) {
            var tmpFile = fil[intI];
            var reader = new FileReader();
            reader.readAsDataURL(tmpFile);
            reader.onload = function (e) {
                url = e.target.result;
                imgss.src = url;
                agoimg.src=url;
            };

            agoimg.onload = function () {
                //等比缩放
                var m = imgss.width / imgss.height;
                Cnv.height =1100;//该值影响缩放后图片的大小
                Cnv.width= 1100*m ;
                //img放入画布中
                //设置起始坐标，结束坐标
                Cntx.drawImage(agoimg, 0, 0,1100*m,1100);
                var Pic = document.getElementById("myCanvas").toDataURL("image/png");

                $.ajax({
                    url:"/Member/saveimg.html",
                    data:{pic:Pic},
                    type:"POST",
                    success: function (data) {
                        if(data.status==1){
                            var html="<img class=\"idimg\" style=\"\" src=\""+data.info+"\"/>" ;
//              + "<span class=\"delespan\">" +
//              "<img class=\"deleimg\" src=\"/Public/newcss/img/delete.png\"/> " +
//              "</span>"
                            $("."+classes).html(html);
                            $('input[name="'+classes+'"]').val(data.info);
                        }else{
                            yjfunc.myconfirm(data.info);
                        }
                    }
                });


            };
        }

    }



</script>

</html>