<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo ($title); ?></title>
<meta http-equiv=X-UA-Compatible content="IE=edge,chrome=1">
<meta name="renderer" content="webkit">
<script>
var ADMIN_PATH="/Admin";
var APP_PATH="";
var CONST_PUBLIC="/Public";
var CONST_UPLOAD="<?php echo U('File/upload');?>";
var CONST_SESSION={}; 
</script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/lib/FontAwesome/css/font-awesome.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/lib/bootstrap3/dist/css/bootstrap.min.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/stylesheets/theme.min.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/stylesheets/custom.css" />
<script type="text/javascript" src="/Public/Admin/lib/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootstrap3/dist/js/bootstrap.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootbox/bootbox.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.custom.js"></script><script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.config.js"></script><script type="text/javascript" src="/Public/Admin/lib/ueditor/ueditor.all.min.js"></script>
<script type="text/javascript">
$(function(){
	$(".myueditor").each(function(index, element) {
        var id=$(element).attr("id");
		var config=$(element).attr("data-config");
		config=((config==""||config==undefined)?$ueconfig:config);
		UE.getEditor(id,config);
    }); 
});
</script>
<script language="javascript">
$(function(){
	InitArea();
});
</script>

  <script type="text/javascript" src="/Public/Admin/lib/ztree/js/jquery.ztree.all-3.5.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/popup/dist/jquery.magnific-popup.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/Public/Admin/lib/ztree/css/zTreeStyle/zTreeStyle.css" /><link rel="stylesheet" type="text/css" href="/Public/Admin/lib/popup/dist/magnific-popup.css" />
  <style>
    #nodeTree {
      margin-top: 0px;
      border: none;
    }
    #nodeTree ul, #nodeTree li {
      width: 100%;
    }
    ul.ztree {
      width:auto;
      margin: 0px;
      overflow-x: hidden;
    }
    .ztree li span.button.ico_open {
      background: url(/Public/Admin/lib/ztree/css/zTreeStyle/img/diy/a1.png) no-repeat 0px 0px;
    }
    .ztree li span.button.ico_close {
      background: url(/Public/Admin/lib/ztree/css/zTreeStyle/img/diy/a2.png) no-repeat 0px 0px;
    }
    .ztree li span.button.ico_docu {
      background: url(/Public/Admin/lib/ztree/css/zTreeStyle/img/diy/a3.png) no-repeat 0px 0px;
    }
    #nodeTree_1_switch {
      visibility: hidden;
      width: 1px;
    }
    /*会员信息*/
    #memberbox {
      position: relative;
      float: left;
      width: 99%;
      border: 1px #ddd solid;
    }
    #memberinfo {
      position: absolute;
      right: 0px;
      top: 0px;
      width: 240px;
      height: 100%;
      display: none;
      overflow: hidden;
      border-left:1px #ddd solid;
    }
    #memberinfo .MainTbl{margin:10px;}
  </style>


</head>
<body>
<div class="row">
  <div id="mask" style="display:none;position: fixed;top: 0px;left:0px; width: 100%;height: 100%;background-color: rgba(0,0,0,0.5);z-index: 999;">
    <div id="mainbody" style="position: relative;top: 0px;left:0px;margin: 5% 10%;width: 80%;height: 80%; background-color: white;box-shadow:10px 10px 5px #0000004f;">
      <div id="maintitle" style="position: relative;width: 100%;height:6%;line-height:30px; text-align:center;border-bottom: 1px dashed lightgray;"><span id="masktitle">接口数据</span> <span onclick="closemask(0)" style="margin-right: 10px;cursor: pointer;" class="pull-right"><i class="fa fa-close"></i></span></div>
      <div id="info" style="text-align: center;overflow-y:scroll;height: 90%;width: 100%;">


      </div>
      <div id="loadingimg" style="display:none;position:absolute;width: 30%;margin-left: 35%;top: 30px;;"><img src="/Public/Home/images/loading.gif"/></div>
    </div>
  </div>
  <div class="col-md-12 " >
    <h2><?php echo ($title); ?></h2>
  </div>
  <div class="col-md-12 " >
    <canvas id="myCanvas" style="display: none;"></canvas>
<img id="agoimg" style="display:none;"/>
    <form action="" method="post" name="form1" id="form1" class="ajaxformx">
      <input type="hidden" id="id" name="id" value="<?php echo ($db["id"]); ?>" />
      <div class="fancy-tab-container">
        <ul class="nav nav-tabs fancy">
          <li class="active"><a href="#autotab_1" data-toggle="tab">基本信息</a></li>
          <li><a href="#autotab_4" data-toggle="tab">认证信息</a></li>
          <li><a href="#autotab_5" data-toggle="tab">信用报告</a></li>
          
<?php
foreach ($blackAllArr as $taskType => $row) { echo '<li><a href="#autotab_'.$taskType.'" data-toggle="tab">'.$row['title'].'</a></li>'; } ?>

          <?php if(!empty($db["openid"])): ?><li><a href="#autotab_2" data-toggle="tab">微信资料</a></li><?php endif; ?>
          <!--<li><a href="#autotab_3" data-toggle="tab">会员关系</a></li>-->
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="autotab_1">
            <div class="col-md-10">
              <div class="form-group">
                <label class="control-label">用户名：</label>
                <div class="controls"> <input type="text" class="form-control w150" name="username" id="username" placeholder="输入<?php echo ($name); ?>用户名" value="<?php echo ($db["username"]); ?>"
                  <?php if(($db["id"]) > "0"): ?>readonly="readonly"<?php endif; ?>
                  />
                  <a onclick="getmemberbaogao('carrier','运营商数据',0);" href="javascript:void(0);">获取运营商报告</a></div>
              </div>
              <hr />
              <div class="form-group">
                <label class="control-label">联系电话：</label>
                <div class="controls">
                  <input type="text" class="form-control" name="telephone" id="telephone" placeholder="输入<?php echo ($name); ?>联系电话" value="<?php echo ($db["telephone"]); ?>" />
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">身份证号：</label>
                <div class="controls">
                  <input type="text" class="form-control" name="idcard" id="idcard" placeholder="输入<?php echo ($name); ?>身份证号" value="<?php echo ($db["idcard"]); ?>" />
                </div>
              </div>

              <div class="row">
                <div class="col-md-4">
                  <div class="thumbnail">

                    <?php if(empty($db["idcardimg1"])): ?><a class="image-popup-vertical-fit"  href="<?php echo ($db["idcardimg1"]); ?>" ><img id="idcardimg1" style="height:200px; " src="/Public/newcss1/img/1.png" alt="身份证正面"></a>
                      <?php else: ?>
                      <a class="image-popup-vertical-fit" href="<?php echo ($db["idcardimg1"]); ?>"><img id="idcardimg1" style="height:200px; " src="<?php echo ($db["idcardimg1"]); ?>" alt="身份证正面"></a><?php endif; ?>
                    <input type="hidden" name="idcardimg1" value="<?php echo ($db["idcardimg1"]); ?>"/>
                    <input type="file" style="display: none;" class="idcardimg1" value="上传图片"
                           onchange="getnewUrl(this.files,'idcardimg1');"/>
                    <div class="caption">
                      <h3>身份证正面</h3>
                      <p>
                        <a href="javascript:void(0);" onclick="$('.idcardimg1').click();" class="btn btn-default" role="button">重新上传</a>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="thumbnail">
                    <?php if(empty($db["idcardimg1"])): ?><a class="image-popup-vertical-fit" href="<?php echo ($db["idcardimg2"]); ?>"><img id="idcardimg2" style="height:200px; " src="/Public/newcss1/img/1.png" alt="身份证反面"></a>
                      <?php else: ?>
                      <a class="image-popup-vertical-fit" href="<?php echo ($db["idcardimg2"]); ?>"><img id="idcardimg2" style="height:200px; " src="<?php echo ($db["idcardimg2"]); ?>" alt="身份证反面"></a><?php endif; ?>
                    <input type="hidden" name="idcardimg2" value="<?php echo ($db["idcardimg2"]); ?>"/>
                    <input type="file" style="display: none;" class="idcardimg2" value="上传图片"
                           onchange="getnewUrl(this.files,'idcardimg2');"/>
                    <div class="caption">
                      <h3>身份证反面</h3>
                      <p>
                        <a href="javascript:void(0);" onclick="$('.idcardimg2').click();" class="btn btn-default" role="button">重新上传</a>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="thumbnail">
                    <?php if(empty($db["idcardimg1"])): ?><a class="image-popup-vertical-fit" href="<?php echo ($db["idcardimg3"]); ?>"><img id="idcardimg3" style="height:200px; " src="/Public/newcss1/img/1.png" alt="手持身份证"></a>
                      <?php else: ?>
                      <a class="image-popup-vertical-fit" href="<?php echo ($db["idcardimg3"]); ?>"><img id="idcardimg3" style="height:200px; " src="<?php echo ($db["idcardimg3"]); ?>" alt="手持身份证"></a><?php endif; ?>

                    <input type="hidden" name="idcardimg3" value="<?php echo ($db["idcardimg3"]); ?>"/>
                    <input type="file" style="display: none;" class="idcardimg3" value="上传图片"
                           onchange="getnewUrl(this.files,'idcardimg3');"/>
                    <div class="caption">
                      <h3>手持身份证</h3>
                      <p>
                        <a href="javascript:void(0);" onclick="$('.idcardimg3').click();" class="btn btn-default" role="button">重新上传</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">状态：</label>
                <div class="controls">
                  <select class="form-control w80 " name="status" id="status">
                    <?php if(is_array($statuslist)): $i = 0; $__LIST__ = $statuslist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(!empty($db["id"])): if(($db["status"]) == $key): ?><option value="<?php echo ($key); ?>" selected="selected"><?php echo ($vo); ?></option>
                          <?php else: ?>
                          <option value="<?php echo ($key); ?>" ><?php echo ($vo); ?></option><?php endif; ?>
                        <?php else: ?>
                        <?php if(($key) == "1"): ?><option value="<?php echo ($key); ?>" selected="selected"><?php echo ($vo); ?></option>
                          <?php else: ?>
                          <option value="<?php echo ($key); ?>" ><?php echo ($vo); ?></option><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group" style="padding:20px 0px;">
                  <div class="controls">
                    <hr />
                    <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
                    <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">紧急联系人</div>
                <div class="panel-body">
                  <?php
 $contacts=json_decode($db['contactinfo'],true); $num=count($contacts); ?>
                  <table>
                    <tr>
                      <td class="col-md-2">关系</td>
                      <td class="col-md-4">姓名</td>
                      <td class="col-md-6">联系电话</td>
                    </tr>
                    <?php if($num > 0): if(is_array($contacts)): foreach($contacts as $k=>$vo): ?><tr style="height: 30px;line-height: 20px;">
                          <td class="col-md-2">
                            <input type="hidden" name="seq[]" value="<?php echo ($k); ?>"/>
                            <select name="relationship[]"  style="color: #999;background: #fff;">
                              <option class="tab" value="" style="color: #999 !important;" disabled selected>关系</option>
                              <option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                              <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                              <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                              <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                              <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                              <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                              <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                              <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                            </select>
                          </td>
                          <td class="col-md-4"> <input style="width: 100px;" type="text" name="contusername[]" value="<?php echo ($vo["username"]); ?>" placeholder="请输入联系人姓名" /></td>
                          <td class="col-md-6"> <input style="width: 100px;" type="text" name="conttelephone[]" value="<?php echo ($vo["telephone"]); ?>" placeholder="请输入联系人电话" /></td>
                        </tr><?php endforeach; endif; ?>
                      <?php else: ?>
                      <tr style="height: 30px;margin-top: 5px;line-height: 20px;">
                        <td class="col-md-2">
                          <input type="hidden" name="seq[]" value="0"/>
                          <select name="relationship[]"  style="color: #999;background: #fff;">
                            <option class="tab" value="" style="color: #999 !important;" disabled selected>关系</option>
                            <option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                            <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                            <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                            <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                            <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                            <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                            <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                            <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                          </select>
                        </td>
                        <td class="col-md-4"> <input style="width: 100px;" type="text" name="contusername[]" value="" placeholder="请输入联系人姓名" /></td>
                        <td class="col-md-6"> <input style="width: 100px;" type="text" name="conttelephone[]" value="" placeholder="请输入联系人电话" /></td>
                      </tr>
                      <tr style="height: 30px;margin-top: 5px;line-height: 20px;">
                        <td class="col-md-2">
                          <input type="hidden" name="seq[]" value="1"/>
                          <select name="relationship[]"  style="color: #999;background: #fff;">
                            <option class="tab" value="" style="color: #999 !important;" disabled selected>关系</option>
                            <option <?php if($vo['relationship'] == 1): ?>selected<?php endif; ?> value="1">父亲</option>
                            <option <?php if($vo['relationship'] == 2): ?>selected<?php endif; ?> value="2">母亲</option>
                            <option <?php if($vo['relationship'] == 3): ?>selected<?php endif; ?> value="3">配偶</option>
                            <option <?php if($vo['relationship'] == 4): ?>selected<?php endif; ?> value="4">子女</option>
                            <option <?php if($vo['relationship'] == 5): ?>selected<?php endif; ?> value="5">朋友</option>
                            <option <?php if($vo['relationship'] == 6): ?>selected<?php endif; ?> value="6">同事</option>
                            <option <?php if($vo['relationship'] == 7): ?>selected<?php endif; ?> value="7">同学</option>
                            <option <?php if($vo['relationship'] == 8): ?>selected<?php endif; ?> value="8">亲属</option>
                          </select>
                        </td>
                        <td class="col-md-4"> <input style="width: 100px;" type="text" name="contusername[]" value="" placeholder="请输入联系人姓名" /></td>
                        <td class="col-md-6"> <input style="width: 100px;" type="text" name="conttelephone[]" value="" placeholder="请输入联系人电话" /></td>
                      </tr><?php endif; ?>
                  </table>
                </div>
              </div>

            </div>
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">运营商信息</div>
                <div class="panel-body">
                  <?php
 $tmobileinfo=json_decode($db['tmobileinfo'],true); ?>
                  手机号：<input name="tmobiletelephone" value="<?php echo ($tmobileinfo["telephone"]); ?>"/><br/>
                  <br/>
                  服务密码：<input name="tmobileservicepwd" value="<?php echo ($tmobileinfo["servicepwd"]); ?>"/>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">芝麻分</div>
                <div class="panel-body">
                  <input name="zmf" value="<?php echo ($db['zmfinfo']); ?>"/>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">银行卡信息</div>
                <div class="panel-body">
                  <?php
 $bankinfo=json_decode($db['bankinfo'],true); ?>
                  姓名：<input name="bankusername" value="<?php echo ($bankinfo["username"]); ?>"/>
                  &nbsp;&nbsp;
                  联系电话：<input name="banktelephone" value="<?php echo ($bankinfo["telephone"]); ?>"/>
                  &nbsp;&nbsp;
                  身份证号：<input name="bankidcard" value="<?php echo ($bankinfo["idcard"]); ?>"/>
                  &nbsp;&nbsp;
                  银行卡号：<input name="bankbankno" value="<?php echo ($bankinfo["bankno"]); ?>"/>
                  &nbsp;&nbsp;
                  银行名：<input name="bankbankname" value="<?php echo ($bankinfo["name"]); ?>"/>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="panel panel-default">
                <div class="panel-heading">工作信息</div>
                <div class="panel-body">
                  <?php
 $workinfo=json_decode($db['work'],true); ?>
                  从事行业：<input name="trade" value="<?php echo ($workinfo['trade']); ?>"/>
                  &nbsp;&nbsp;
                  工作职位：<input name="work" value="<?php echo ($workinfo['trade']); ?>"/>
                  &nbsp;&nbsp;
                  单位名称：<input name="name" value="<?php echo ($workinfo['name']); ?>"/>
                  &nbsp;&nbsp;
                  单位详细地址：<input name="address" value="<?php echo ($workinfo['address']); ?>"/>
                  &nbsp;&nbsp;
                  借款用途：<select name='use' style="color: #999;background: #fff;" id="use">
                  <option class="tab" value="" style="color: #999 !important;" disabled selected>请选择用途</option>
                  <option <?php if($workinfo['use'] == 1): ?>selected<?php endif; ?> value="1">租房</option>
                  <option <?php if($workinfo['use'] == 2): ?>selected<?php endif; ?> value="2">手机数码</option>
                  <option <?php if($workinfo['use'] == 3): ?>selected<?php endif; ?> value="3">健康医疗</option>
                  <option <?php if($workinfo['use'] == 4): ?>selected<?php endif; ?> value="4">旅游</option>
                  <option <?php if($workinfo['use'] == 5): ?>selected<?php endif; ?> value="5">家具家居</option>
                  <option <?php if($workinfo['use'] == 6): ?>selected<?php endif; ?> value="6">其他</option>
                </select>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="thumbnail">
                        <a class="image-popup-vertical-fit" href="<?php echo ($workinfo['workimg']); ?>"><img id="workimg" style="height:200px; " src="<?php echo ($workinfo['workimg']); ?>" alt="工作凭证"></a>
                        <input type="hidden" name="workimg" value="<?php echo ($workinfo['workimg']); ?>"/>

                        <h3>工作凭证</h3>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>


            <div class="row">
              <div class="col-md-12">
                <div class="form-group" style="padding:20px 0px;">
                  <div class="controls">
                    <hr />
                    <button type="submit" class="btn btn-success" ><i class="fa fa-save"></i> 提交</button>
                    <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
                  </div>
                </div>
              </div>
            </div>
          </div>



          <div class="clearfix"></div>
          <div class="tab-pane" id="autotab_2">
            <div class="col-md-6">
            
              <div class="form-group">
                <label class="control-label">openid：</label>
                <div class="controls">
                 <?php echo ($db["openid"]); ?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">头像：</label>
                <div class="controls">
                 <a href="<?php echo ((isset($db["headimgurl"]) && ($db["headimgurl"] !== ""))?($db["headimgurl"]):C('DEFAULT_AVATAR')); ?>" target="_blank"><img src="<?php echo ((isset($db["headimgurl"]) && ($db["headimgurl"] !== ""))?($db["headimgurl"]):C('DEFAULT_AVATAR')); ?>" alt="<?php echo ($db["nickname"]); ?>" width="80" /></a>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label">昵称：</label>
                <div class="controls">
                 <?php echo ($db["nickname"]); ?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">关注：</label>
                <div class="controls">
                 <?php if(($db["subscribe"]) == "1"): ?>已关注 <?php echo (time_format($db["subscribe_time"])); else: ?>未关注<?php endif; ?>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">性别：</label>
                <div class="controls">
                 <?php if(($db["sex"]) == "2"): ?>女<?php else: ?>男<?php endif; ?>
                </div>
              </div>
              
              <div class="form-group">
                <label class="control-label">所在地：</label>
                <div class="controls">
                 <?php echo ($db["country"]); ?> <?php echo ($db["province"]); ?> <?php echo ($db["city"]); ?>
                </div>
              </div>
              
              
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="clearfix"></div>
          <div class="tab-pane" id="autotab_3">
            <div class="col-md-12">

              <div class="form-group">
                <label class="control-label">下级列表：</label>
                <div class="controls">
                  <div id="memberbox">
                    <ul id="nodeTree" class="ztree">
                    </ul>
                    <div id="memberinfo">
                      <table border="0" cellspacing="1" cellpadding="5" class="MainTbl">
                        <tr class="toolbar">
                          <td colspan="2" class="tc" onclick="$('#memberinfo').hide();">基本信息</td>
                        </tr>
                        <tr class="row0">
                          <td  colspan="2" align="center">
                            <img id="info-headimgurl" src=""  style="width:100px; height:100px; border-radius:50%;"/>
                          </td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >会员昵称：</td>
                          <td  ><span id="info-username"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >会员姓名：</td>
                          <td  ><span id="info-userreal"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >身份证号：</td>
                          <td  ><span id="info-idcard"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >联系电话：</td>
                          <td  ><span id="info-telephone"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >消费积分：</td>
                          <td  ><span id="info-credit"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >佣金账户：</td>
                          <td  ><span id="info-balance"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >会员等级：</td>
                          <td  ><span id="info-level"></span></td>
                        </tr>
                        <tr class="row0">
                          <td class="col1" >关注时间：</td>
                          <td  ><span id="info-addtime"></span></td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="clearfix"></div>
            </div>
          </div>
          <div class="clearfix"></div>

          <!--//运营商报告-->
          <div class="tab-pane" id="autotab_5">
            <style>


              .box {
                width: 90%;
              }

              .table {
                margin: 0 auto 30px;
                border: 0px;
              }

              .tabbox {
                padding: 0;
                width: 100%;
                overflow-x: scroll;
              }

              table {
                width: 100%;
                border: none;
                border-collapse: collapse;
                margin: 0 auto;
              }

              table td {
                white-space: normal;
              }

              .center {
                text-align: center;
              }

              .left {
                padding-left: 10px;
                text-align: left;
              }

              th {
                text-align: center;
                height: 30px;
                border: 1px solid #ccc;
              }

              .th {
                background: rgb(70, 140, 180);
              }

              td {
                height: 30px;
                border: 1px solid #ccc;
                white-space: normal;
              }

              .sort {
                height: 30px;
                line-height: 30px;
                width: 100%;
                margin: 0 auto;
                font-size: 12px;
                color: rgb(119, 119, 119);
                text-align: left;
                font-weight: 100;
                padding: 0;
              }

              .h5 {
                width: 100%;
                height: 30px;
                margin: 0 auto;
                border-bottom: none;
                color: white;
                background: rgb(30, 83, 180);
                line-height: 30px;
                text-align: center;
              }

              h3 {
                font-size: 18px;
                font-weight: 700;
                width: 100%;
                margin: 30px auto;
              }

              .dropdown-menu {
                z-index: 10000;
              }

              table tr {
                margin: 10px;
                border-bottom: solid 1px #f1f1f1;
                border-top: solid 1px #ccc;
              }

              .hideborder {
                border: none;
                width: 100px;
                background-color: #f1f1f1;
                border-radius: 5px;
              }

              .rightpass {
                color: white;
                background: #32CD32;
                padding: 5px;
                font-size: 10px;
                border-radius: 5px;
              }
              .rightpass0 {
                color: white;
                background: #32CD32;
                padding: 5px;
                font-size: 10px;
                border-radius: 5px;
              }
              .rightpass1 {
                color: white;
                background: #e83e8c;
                padding: 5px;
                font-size: 10px;
                border-radius: 5px;
              }
              .inblacklist {
                color: white;
                background: #DC143C;
                padding: 5px;
                font-size: 10px;
                border-radius: 5px;
              }

              .iconphoto {
                background-image: url('/images/people.png');
              }

              .icon-gou {
                background-image: url("/images/gou.png");
              }

              .icon-alarm {
                background-image: url("/images/alarm.png");
              }

              .detail-company-name {
                color: white;
                background: #336bdc;
                padding: 5px;
                font-size: 10px;
                border-radius: 5px;
              }

              .verificationPass {
                color: #32CD32;
              }

              .verificationFlase {
                color: #DC143C;
              }
            </style>
            <div class="box">
              <input type="hidden" name="inputliveaddress" id="inputliveaddress" value="">

              <h3 style="text-align: center;margin: 10px">运营商资信报告</h3>
              <div class="row">
                <div><span style="float: left;margin-left: 2%">编号：<?php echo ($allrows['taskNo']); ?></span><span style="float: right">报告时间：<?php
 echo date("Y-m-d H:i:s",intval(time())); ?></span></div>
              </div>
              <div class="table">
                <h5 class="h5">用户基本信息</h5>
                <div class="tabbox">
                  <table>
                    <table>
                      <tbody id="searchResultTable">

                      <tr>
                        <td class="hideborder" style="text-align: right;margin: 10px 5px 10px 0;">
                          姓名：
                        </td>
                        <td style="border: none" colspan="2"><?php echo ($member['username']); ?></td>

                      </tr>

                      <tr>
                        <td class="hideborder" style="text-align: right">身份证：</td>
                        <td style="border: none">
                          <?php echo ($member['idcard']); ?> &nbsp;&nbsp;
                          <span class="rightpass<?php echo ($isBan); ?>"><?php echo ($isBan?'在网贷黑名单里':'不在网贷黑名单里'); ?></span>
                          <span class="rightpass<?php echo ($isb); ?>"><i class="iconphoto"></i><?php echo ($isb?'有犯罪记录':'没有犯罪记录'); ?></span>
                        </td>

                      </tr>
                      <tr>
                        <td class="hideborder" style="text-align: right">营运商提供：</td>
                        <td style="border: none">
                           &nbsp;&nbsp;
                          <span class="rightpass">姓名：<?php echo ($userInfo['name']); ?></span>
                        </td>

                      </tr>
                      <tr>
                        <td class="hideborder" style="text-align: right">手机号：</td>
                        <td style="border: none">运营商:<?php echo ($userInfo['operator']==-1?'获取失败':$userInfo['operator']); ?>
                           | 入网时间：<?php echo ($userInfo1["openDate"]); ?> | 开户时长(天)：<?php echo ($userInfo['inNetDuration']==-1?'获取失败':$userInfo['inNetDuration']); ?> | 最近6个月有效通话记录月数：<?php echo ($userInfo['activeMonths']==-1?'获取失败':$userInfo['activeMonths']); ?>
						   | 停机次数：<?php echo ($userInfo['outOfServiceTimes']); ?>   | 	末次通话所在地：<?php echo ($userInfo['lastCallLocation']==-1?'获取失败':$userInfo['lastCallLocation']); ?>
						   
                        </td>

                      </tr>
                      <tr>
                        <td class="hideborder" style="text-align: right">认证：</td>
                        <td style="border: none"><span class="rightpass"><?php echo ($userInfo1["phoneNo"]); ?></span>
                          <span class="rightpass"><i class="iconphoto"></i>手机实名认证：<?php echo ($realname['taskResult']['message']); ?></span>
                        </td>

                      </tr>
                      <tr>
                        <td class="hideborder" style="text-align: right">套餐：</td>
                        <td style="border: none">
                          余额：<?php echo ($userInfo['balance']==-1?'获取失败':$userInfo['balance']); ?> (元)
                        </td>
                        <td style="border: none">
                          归属地：<?php echo ($userInfo['attribution']?$userInfo['attribution']:'获取失败'); ?>
                        </td>
                      </tr>
                      <tr>
                        <td class="hideborder" style="text-align: right">居住地址：</td>
                        <td style="border: none"><div id="inputliveaddressdiv"><?php echo ($userInfo1['address']?$userInfo1['address']:'获取失败'); ?></div></td>
                        <td style="border: none">

                        </td>

                      </tr>

                      </tbody>
                    </table>
                  </table>
                </div>
              </div>
              <!--table2-->
              <!--<div class="table">
                <div class="table">
                  <h5 class="h5">短信历史</h5>
                  <div class="tabbox">
                    <table>
                      <thead>

                      <tr class="center hideborder" style="text-align: center">
                        <td> 年月 </td>
                        <td>
                          短信详情
                        </td>
                      </tr>
                      </thead>
                      <tbody>
                      <?php if(is_array($smsHistory)): $i = 0; $__LIST__ = $smsHistory;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                          <?php
 $nums=count($vo['details']); if($nums>50){ $nums=50; } ?>
                          <td rowspan="<?php echo ($nums+1); ?>"><?php echo ($vo['month']); ?></td>
                        </tr>

                        <style>
                          .smsdetail td ul{list-style: none;}
                          .smsdetail td ul li{
                            float: left;width: 25%; position: relative;text-align: left;}

                        </style>

                          <?php if(is_array($vo["details"])): $k = 0; $__LIST__ = $vo["details"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vod): $mod = ($k % 2 );++$k; if($k <= 50): ?><tr class="center smsdetail">
                              <td>
                                <ul>
                                  <li>对方号码:<?php echo ($vod['otherPhone']); ?></li>
                                  <li>费用:<?php echo ($vod['fee']); ?></li>
                                  <li>信息类型：<?php echo ($vod['smsType']?'接受':'发送'); ?></li>
                                  <li> 短信发送时间 ：<?php echo ($vod['date']); ?></li>
                                </ul>
                              </td>
                            </tr><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>-->
             <!-- <div class="table">
                <div class="table">
                  <h5 class="h5">通话历史</h5>
                  <div class="tabbox">
                    <table>
                      <thead>

                      <style>
                        .calldetail td ul{list-style: none;}
                        .calldetail td ul li{
                          float: left;width: 16%; position: relative;text-align: left;}
                      </style>

                      <tr class="center hideborder" style="text-align: center">
                        <td> 年月 </td>
                        <td>
                          通话详情
                        </td>
                      </tr>
                      </thead>
                      <tbody>
                      <?php if(is_array($callHistory)): $i = 0; $__LIST__ = $callHistory;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                          <?php
 $nums=count($vo['details']); if($nums>50){ $nums=50; } ?>
                          <td rowspan="<?php echo ($nums+1); ?>"><?php echo ($vo['month']); ?></td>
                        </tr>
                        <?php if(is_array($vo["details"])): $k = 0; $__LIST__ = $vo["details"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vod): $mod = ($k % 2 );++$k; if($k <= 50): ?><tr class="center calldetail">
                              <td>
                                <ul>
                                  <li>通话时长:<?php echo ($vod['duration']); ?>秒</li>
                                  <li>对方号码:<?php echo ($vod['otherPhone']); ?></li>
                                  <li>通话费用（分）:<?php echo ($vod['fee']); ?></li>
                                  <li>通话开始间:<?php echo ($vod['startTime']); ?></li>
                                  <li>通话区域:<?php echo ($vod['callLocation']); ?></li>
                                  <li>通话类型:<?php echo ($vod['callType']?'被叫':'主叫'); ?></li>
                                </ul>
                            </td>
                            </tr><?php endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>-->

             <!-- </div>
              <div class="table">
                <h5 class="h5">账单记录</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr class="center hideborder" style="text-align: center">
                      <td style="width: 20%">账单月，（格式：yyyyMM）</td>
                      <td style="width: 20%"> 套餐及固定费 （单位：分），-1:爬取不到的默认值 </td>
                      <td style="width: 20%">套餐外语音费用（单位：分），-1:爬取不到的默认值</td>
                      <td style="width: 20%"> 增值业务费 （单位：分），-1:爬取不到的默认值 </td>
                      <td style="width: 20%">本月总费用 （单位：分），-1:爬取不到的默认值</td>
                    </tr>
                    </thead>
                    <tbody>
                      <?php if(is_array($billHistory)): $i = 0; $__LIST__ = $billHistory;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                      <td><?php echo ($vo['yearMonth']); ?></td>
                      <td><?php echo ($vo['baseFee']); ?></td>
                      <td><?php echo ($vo['voiceFee']); ?></td>
                      <td><?php echo ($vo['extraFee']); ?></td>
                      <td><?php echo ($vo['totalFee']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>-->
			  <div class="table">
                <h5 class="h5">消费详情</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr class="center hideborder" style="text-align: center">
                      <td style="width: 20%">月租类型</td>
                      <td style="width: 20%"> 剩余积分 </td>
                      <td style="width: 20%">剩余彩信条数</td>
                      <td style="width: 20%"> 已用流量（MB） </td>
                      <td style="width: 20%">剩余流量（MB）</td>
                      <td style="width: 20%">近3个月累计充值金额（元）</td>
                    </tr>
                    </thead>
                    <tbody>
                      <?php if(is_array($$expenses)): $i = 0; $__LIST__ = $$expenses;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                      <td><?php echo ($vo['monthlyRentType']); ?></td>
                      <td><?php echo ($vo['remainingBonusPoint']); ?></td>
                      <td><?php echo ($vo['remainingMms']); ?></td>
                      <td><?php echo ($vo['usedTraffic']); ?></td>
                      <td><?php echo ($vo['remainingTraffic']); ?></td>
                      <td><?php echo ($vo['recharges180d']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
			  <div class="table">
                <h5 class="h5">充值详情</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr class="center hideborder" style="text-align: center">
                      <td style="width: 20%">充值日期</td>
                      <td style="width: 20%"> 充值金额（元） </td>
                    
                    </tr>
                    </thead>
                    <tbody>
                      <?php if(is_array($$recharges)): $i = 0; $__LIST__ = $$recharges;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                      <td><?php echo ($vo['date']); ?></td>
                      <td><?php echo ($vo['amount']); ?></td>                  
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">呼入排行（最近90天呼出次数的前20名）</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr class="center hideborder" style="text-align: center">
                      <td> 排名 </td>
                      <td>地域（精确到省）</td>
                      <td>电话号码数量</td>
                      <td>所占百分比（0~1）</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($incomingCallsRanking)): $i = 0; $__LIST__ = $incomingCallsRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                      <td><?php echo ($vo['rank']); ?></td>
                      <td><?php echo ($vo['attribution']); ?></td>
                      <td><?php echo ($vo['phoneNos']); ?></td>
                      <td><?php echo ($vo['percent']); ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">呼出排行（最近90天呼出次数的前20名）</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr class="center hideborder" style="text-align: center">
                      <td> 排名 </td>
                      <td>地域（精确到省）</td>
                      <td>电话号码数量</td>
                      <td>所占百分比（0~1）</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($outgoingCallsRanking)): $i = 0; $__LIST__ = $outgoingCallsRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                        <td><?php echo ($vo['rank']); ?></td>
                        <td><?php echo ($vo['attribution']); ?></td>
                        <td><?php echo ($vo['phoneNos']); ?></td>
                        <td><?php echo ($vo['percent']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">紧密联系人通话情况</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr>
                      <td class="hideborder">
                        <div>联系人通话情况</div>
                      </td>
                      <td colspan="2" style="border: none;"></td>
                    </tr>
                    <tr class="center hideborder" style="text-align: center">
                      <td>姓名</td>
                      <td>关系</td>
                      <td>手机号</td>
                      <td>归属地（精确到地级市）</td>
                      <td>末次通话时间</td>
                      <td>近30天通话次数</td>
                      <td>近30天平均通话时长（秒）</td>
                      <td>近90天通话次数</td>
                      <td>近90天平均通话时长（秒）</td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($closelyContacts)): $i = 0; $__LIST__ = $closelyContacts;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                      <td><?php echo ($vo['name']); ?></td>
                      <td><?php echo ($vo['relationship']); ?></td>
                      <td><?php echo ($vo['phoneNo']); ?></td>
                      <td><?php echo ($vo['attribution']); ?></td>
                      <td><?php echo ($vo['lastCallTime']); ?></td>
                      <td><?php echo ($vo['numOfCalls30d']); ?></td>
                      <td><?php echo ($vo['avgCallDuration30d']); ?></td>
                      <td><?php echo ($vo['numOfCalls90d']); ?></td>
                      <td><?php echo ($vo['avgCallDuration90d']); ?></td>
                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">联系人通话排行（近30天通话次数排名前100名）</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr>
                      <td class="hideborder">
                        <div>通话排行</div>
                      </td>
                      <td colspan="2" style="border: none;"></td>
                    </tr>
                    <tr class="center hideborder" style="text-align: center">
                      <td>次数排名</td>
                      <td>时长排名</td>
                      <td>电话号码</td>
                      <td>电话标签</td>
                      <td>近30天呼入次数</td>
                      <td>近30天呼入时长（秒）</td>
                      <td>近30天呼出次数</td>
                      <td>近30天呼出时长（秒）</td>
                      <td> 近90天呼入次数 </td>
                      <td> 近90天呼入时长（秒） </td>
                      <td>  近90天呼出次数  </td>
                      <td> 近90天呼出时长（秒） </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($contactsRanking)): $i = 0; $__LIST__ = $contactsRanking;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                        <td><?php echo ($vo['timesRanking']); ?></td>
                        <td><?php echo ($vo['durationRanking']); ?></td>
                        <td><?php echo ($vo['phoneNo']); ?></td>
                        <td><?php echo ($vo['tag']); ?></td>
                        <td><?php echo ($vo['incomingCalls30d']); ?></td>
                        <td><?php echo ($vo['incomingDuration30d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls30d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration30d']); ?></td>
                        <td><?php echo ($vo['incomingCalls90d']); ?></td>
                        <td><?php echo ($vo['incomingDuration90d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls90d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration90d']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">通话分时统计（按每小时）</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr>
                      <td class="hideborder">
                        <div>通话分时统计</div>
                      </td>
                      <td colspan="2" style="border: none;"></td>
                    </tr>
                    <tr class="center hideborder" style="text-align: center">
                      <td> 时段（00~23） </td>
                      <td>近30天呼入次数</td>
                      <td>近30天呼入时长（秒）</td>
                      <td> 近30天呼出次数 </td>
                      <td> 近30天呼出时长（秒） </td>
                      <td> 近90天呼入次数 </td>
                      <td>近90天呼入时长（秒）</td>
                      <td> 近90天呼出次数 </td>
                      <td> 近90天呼出时长（秒）  </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($callDistributionByTime)): $i = 0; $__LIST__ = $callDistributionByTime;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                        <td><?php echo ($vo['oClock']); ?></td>
                        <td><?php echo ($vo['incomingCalls30d']); ?></td>
                        <td><?php echo ($vo['incomingDuration30d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls30d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration30d']); ?></td>
                        <td><?php echo ($vo['incomingCalls90d']); ?></td>
                        <td><?php echo ($vo['incomingDuration90d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls90d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration90d']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="table">
                <h5 class="h5">通话分类统计</h5>
                <div class="tabbox">
                  <table>
                    <thead>
                    <tr>
                      <td class="hideborder">
                        <div>通话分类统计</div>
                      </td>
                      <td colspan="2" style="border: none;"></td>
                    </tr>
                    <tr class="center hideborder" style="text-align: center">
                      <td> 电话标签</td>
                      <td>电话号码数量</td>
                      <td> 近30天呼入次数</td>
                      <td>  近30天呼入时长（秒）  </td>
                      <td> 近30天呼出次数 </td>
                      <td> 近30天呼出时长（秒） </td>
                      <td>近90天呼入次数</td>
                      <td> 近90天呼入时长（秒）</td>
                      <td>  近90天呼出次数  </td>
                      <td>   近90天呼出时长（秒）   </td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($taggedCallsAggregation)): $i = 0; $__LIST__ = $taggedCallsAggregation;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="center">
                        <td><?php echo ($vo['tag']); ?></td>
                        <td><?php echo ($vo['phoneNos']); ?></td>
                        <td><?php echo ($vo['incomingCalls30d']); ?></td>
                        <td><?php echo ($vo['incomingDuration30d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls30d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration30d']); ?></td>
                        <td><?php echo ($vo['incomingCalls90d']); ?></td>
                        <td><?php echo ($vo['incomingDuration90d']); ?></td>
                        <td><?php echo ($vo['outgoingCalls90d']); ?></td>
                        <td><?php echo ($vo['outgoingDuration90d']); ?></td>
                      </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>



<!--征信平台-->
<?php
foreach ($blackAllArr as $taskType => $row) { ?>
  <div class="table">
    <h5 class="h5"><?php echo ($row["title"]); ?></h5>
    <div class="tabbox">
      <table>
        <thead>
  <?php
 foreach ($row['response']['taskResult'] as $key => $val) { if(!is_array($val)){ $tabName=getArrVal($fieldsArr[$taskType][$key],'title'); $tabNameStr=getArrVal($fieldsArr[$taskType][$key],$val); if(!$tabName){ $tabName = $key; } if(!$tabNameStr){ $tabNameStr = $val; } echo '<tr>'; echo '<td>'.$tabName.'</td><td>'.$tabNameStr.'</td>'; echo '</tr>'; } } ?>
        </thead>
  </table>
  <?php
 foreach ($row['response']['taskResult'] as $key => $val) { if(is_array($val)){ if(count($val)){ $tabName=getArrVal($fieldsArr[$taskType][$key],'title'); if(!$tabName){ $tabName = $key; } echo '<table><tbody>'; if($taskType==='accdetect'){ echo '<tr>'; $codesStr=''; foreach($val as $key=>$val){ $tabNameStr=getArrVal($fieldsArr[$taskType]['codes'],$val); if(!$tabNameStr){ $tabNameStr = $val; } $codesStr.= $tabNameStr.','; } echo '<td width="200px">'.$tabName.'</td><td>'.$codesStr.'</td>'; echo '</tr>'; } else { echo '<tr>'; echo '<td align="center" colspan="'.count($val[0]).'">'.$tabName.'</td>'; echo '</tr>'; $ii=0; foreach($val as $key=>$rowChild){ if($ii===0){ echo '<tr>'; foreach(array_keys($rowChild) as $key=>$value){ $tabName=getArrVal($fieldsArr[$taskType][$value],'title'); if(!$tabName){ $tabName = $value; } echo '<td>'.$tabName.'</td>'; } echo '</tr>'; } echo '<tr>'; foreach($rowChild as $key=>$val){ $tabNameStr=getArrVal($fieldsArr[$taskType][$key],$val); if(!$tabNameStr){ $tabNameStr = $val; } echo '<td>'.$tabNameStr.'</td>'; } echo '</tr>'; $ii++; } } echo '</tbody></table>'; }}} echo '</div></div>'; } ?>
<!--征信平台-->

            </div>
          </div>
          <div class="clearfix"></div>




          <!--//运营商报告-->

          <!--黑名单信息-->

<!--征信平台-->
<?php
foreach ($blackAllArr as $taskType => $row) { echo '<div class="tab-pane" id="autotab_'.$taskType.'">'; ?>
  <div class="table">
    <h5 class="h5"><?php echo ($row["title"]); ?></h5>
    <h5 class="h6"><?php echo ($row["response"]["message"]); ?></h6>
    <div class="tabbox">
      <table>
        <thead>
  <?php
 foreach ($row['response']['taskResult'] as $key => $val) { if(!is_array($val)){ $tabName=getArrVal($fieldsArr[$taskType][$key],'title'); $tabNameStr=getArrVal($fieldsArr[$taskType][$key],$val); if(!$tabName){ $tabName = $key; } if(!$tabNameStr){ $tabNameStr = $val; } echo '<tr>'; echo '<td>'.$tabName.'</td><td>'.$tabNameStr.'</td>'; echo '</tr>'; } } ?>
        </thead>
  </table>
  <?php
 foreach ($row['response']['taskResult'] as $key => $val) { if(is_array($val)){ if(count($val)){ $tabName=getArrVal($fieldsArr[$taskType][$key],'title'); if(!$tabName){ $tabName = $key; } echo '<table><tbody>'; if($taskType==='accdetect'){ echo '<tr>'; $codesStr=''; foreach($val as $key=>$val){ $tabNameStr=getArrVal($fieldsArr[$taskType]['codes'],$val); if(!$tabNameStr){ $tabNameStr = $val; } $codesStr.= $tabNameStr.','; } echo '<td width="200px">'.$tabName.'</td><td>'.$codesStr.'</td>'; echo '</tr>'; } else { echo '<tr>'; echo '<td align="center" colspan="'.count($val[0]).'">'.$tabName.'</td>'; echo '</tr>'; $ii=0; foreach($val as $key=>$rowChild){ if($ii===0){ echo '<tr>'; foreach(array_keys($rowChild) as $key=>$value){ $tabName=getArrVal($fieldsArr[$taskType][$value],'title'); if(!$tabName){ $tabName = $value; } echo '<td>'.$tabName.'</td>'; } echo '</tr>'; } echo '<tr>'; foreach($rowChild as $key=>$val){ $tabNameStr=getArrVal($fieldsArr[$taskType][$key],$val); if(!$tabNameStr){ $tabNameStr = $val; } echo '<td>'.$tabNameStr.'</td>'; } echo '</tr>'; $ii++; } } echo '</tbody></table>'; }}} echo '</div></div></div><div class="clearfix"></div>'; } ?>
<!--征信平台-->
          <!--黑名单信息-->
        </div>
      </div>

    </form>
  </div>

</div>

<script type="text/javascript" src="/Public/Admin/lib/bootstrapValidator.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() {
var $form= $('.ajaxformx');
	if(typeof($fields)=="undefined"){
		$.rendAjaxForm($form);
	}else{
		$form.find("input:text").eq(0).focus();
		$form.on('success.form.bv', function(e) {
			$.rendAjaxForm($form);
		}).bootstrapValidator({ 
			message: '输入不合法',
			feedbackIcons: {
				valid: 'fa fa-check',
				invalid: 'fa fa-remove',
				validating: 'fa fa-refresh'
			},
			fields: $fields
		}); 
	}
});
</script>
<link rel="stylesheet" type="text/css" href="/Public/Admin/lib/uploadify/uploadify.css" />
<script type="text/javascript" src="/Public/Admin/lib/uploadify/jquery.uploadify.min.js"></script>

<script>


    function getnewUrl(fil,classes) {
        if(fil.length<=0){
            return;
        }
        var Cnv = document.getElementById('myCanvas');
        var Cntx = Cnv.getContext('2d');//获取2d编辑容器
        var imgss =   new Image();
        var agoimg=document.getElementById("agoimg");
        var newimg=document.getElementById(classes);
        newimg.src="/Public/Home/images/loading.gif";
//        $("."+classes).html("<img style='width: 100%; height: 100%;' src=\"/Public/Home/images/loading.gif\"/>");
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
                Cnv.height =700;//该值影响缩放后图片的大小
                Cnv.width= 700*m ;
                //img放入画布中
                //设置起始坐标，结束坐标
                Cntx.drawImage(agoimg, 0, 0,Cnv.width, Cnv.height);
                var Pic = document.getElementById("myCanvas").toDataURL("image/png");

                $.ajax({
                    url:"/Member/saveimg.html",
                    data:{pic:Pic},
                    type:"POST",
                    success: function (data) {
                        if(data.status==1){
//                            var html="<img class=\"idimg\" style=\"\" src=\""+data.info+"\"/>" ;
                            newimg.src=data.info;
//                            $("."+classes).html(html);
                            $('input[name="'+classes+'"]').val(data.info);
                        }else{
                            alerterr(data.info);
                        }
                    }
                });


            };
        }

    }
</script>
<script>
    function getmemberbaogao(type,title,retry){
        var id=<?php echo ($db["id"]); ?>;

        $("#masktitle").html(title);
        closemask(1);
        $("#info").html(" <img id=\"loadingimg\" style=\"position: relative;height: 100%;\" src=\"/Public/Home/images/loading.gif\"/>");
        $.ajax({
            url:ADMIN_PATH+"/Member/getinterfacedata.html",
            data:{type:type,id:id,retry:retry},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    $("#info").html(data.html);
                }else{
                    $("#info").html(data.html);
                    if(retry==0){
                        setTimeout(function () {
                            bootbox.confirm("上次获取接口数据失败，是否重新获取？", function(result) {
                                if (result) {
                                    getmemberbaogao(type,title,1);
                                };
                            });
                        },1000);
                    }

                }

            }
        })


    }

    function closemask(val){
        if(val==1){
            $("#mask").fadeIn();
        }else{
            $("#mask").fadeOut();
        }
    }


    $(document).ready(function() {

        $('.image-popup-vertical-fit').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            mainClass: 'mfp-img-mobile',
            image: {
                verticalFit: true
            }

        });

        $('.image-popup-fit-width').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            image: {
                verticalFit: false
            }
        });

        $('.image-popup-no-margins').magnificPopup({
            type: 'image',
            closeOnContentClick: true,
            closeBtnInside: false,
            fixedContentPos: true,
            mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
            image: {
                verticalFit: true
            },
            zoom: {
                enabled: true,
                duration: 300 // don't foget to change the duration also in CSS
            }
        });

    });

</script>
</body>







</html>