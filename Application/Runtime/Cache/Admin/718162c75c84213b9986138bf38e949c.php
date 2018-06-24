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
<script type="text/javascript" src="/Public/Admin/lib/jquery-1.11.2.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootstrap3/dist/js/bootstrap.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/bootbox/bootbox.min.js"></script><script type="text/javascript" src="/Public/Admin/lib/jquery.custom.js"></script>
<script type="text/javascript" src="/Public/Admin/lib/jquery.form.js"></script>
<script type="text/javascript">
$(document).ready(function() {
var $form= $('.ajaxformx');
	$.rendAjaxForm($form);
});
</script>
<script>
$(function(){
	 $.setMenu = function(){
	var url="<?php echo U('Wechat/setMenu');?>";
	$.ajax({
		"url":url,
		success: function(data){ 
			if(data.status==1){
				alertok(data.info);
			}else{
				alerterr(data.info);	
			}
		}
	});
	 }

	 $.getMenu = function(){
	var url="<?php echo U('Wechat/getMenu');?>";
	$.ajax({
		"url":url,
		success: function(msg){
			
			var obj=eval(msg);
			if(msg.status=="1"){
				alertok("服务器菜单获取成功！");
				$(".form-control").val("");
				//var menu=eval("("+ msg.info + ")"); 
				var json=(msg.info);
				var menu=json.menu;
				var len=(menu.button.length);
				var name,type,url,key,subbtn;
				var subname,subtype,suburl,subkey;
				for(var i=0;i<len;i++){
					 name=(menu.button[i].name);
					 type=(menu.button[i].type);
					 key=(menu.button[i].key);
					 url=(menu.button[i].url);
					 subbtn=menu.button[i].sub_button;
				
					 if(subbtn==undefined){
						 subbtn=[];
					 };
					 if(subbtn.length>0){
						 //有子菜单时，只需赋值一级名称
						 $("#item"+(i+1)+"_1").val(name);
						 $("#item"+(i+1)+"_2").val("");
						 $("#item"+(i+1)+"_3").val("");
						 
						 
						 for(var j=0;j<subbtn.length;j++){
								 subname=(subbtn[j].name);
								 subtype=(subbtn[j].type);
								 subkey=(subbtn[j].key);
								 suburl=(subbtn[j].url);
							 if (subtype=="view"){
								 $("#item"+(i+1)+"_"+(3*(j+1)+1)).val(subname);
								 $("#item"+(i+1)+"_"+(3*(j+1)+2)).val("");
								 $("#item"+(i+1)+"_"+(3*(j+1)+3)).val(suburl);
									
								}else{
								 $("#item"+(i+1)+"_"+(3*(j+1)+1)).val(subname);
								 $("#item"+(i+1)+"_"+(3*(j+1)+2)).val(subkey);
								 $("#item"+(i+1)+"_"+(3*(j+1)+3)).val("");
								}
								
						 }
						 
					 }else{
					 	if (type=="view"){
						 $("#item"+(i+1)+"_1").val(name);
						 $("#item"+(i+1)+"_2").val("");
						 $("#item"+(i+1)+"_3").val(url);
							
						}else{
						 $("#item"+(i+1)+"_1").val(name);
						 $("#item"+(i+1)+"_2").val(key);
						 $("#item"+(i+1)+"_3").val("");
						}
					 }
				}
			}else{ 
				alerterr(msg.info);
			}
		}
		});	
	 }
});
</script>
</head>
<body>
<div class="row">
  <div class="col-md-12 " >
    <div class="col-md-12 custom-tool" >
      <h2 class="pull-left"><?php echo ($title); ?></h2>
      <div class="pull-right">
        <button onclick="$.getMenu()" class="btn btn-info" data-placement="bottom" rel="tooltip" data-original-title="获取服务器菜单"><i class="fa fa-sort-amount-asc"></i> 获取</button>
        <button onclick="$.setMenu()" class="btn btn-success" data-placement="bottom" rel="tooltip" data-original-title="上传菜单到微信服务器"><i class="fa fa-upload"></i> 上传</button>
      </div>
    </div>
  </div>
  <div class="col-md-12 " >
    <form action="" method="post" name="form1" id="form1" class="ajaxformx"> 
      <div class="col-md-12 custom-form">
        <table class="table  display full table-striped custom-table vm">
          <thead>
          <th>菜单</th>
            <th>第一列</th>
            <th>第二列</th>
            <th>第三列</th>
          </tr>
          <tr class="active">
            <td class="col1">一级：</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="16" class="form-control" name="item1_1" id="item1_1" value="<?php echo ($db[0]); ?>"  /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_2" id="item1_2" value="<?php echo ($db[1]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_3" id="item1_3"  value="<?php echo ($db[2]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="16" class="form-control" name="item2_1" id="item2_1"  value="<?php echo ($db[3]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_2" id="item2_2"  value="<?php echo ($db[4]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_3" id="item2_3"  value="<?php echo ($db[5]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="16" class="form-control" name="item3_1" id="item3_1"  value="<?php echo ($db[6]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_2" id="item3_2"  value="<?php echo ($db[7]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_3" id="item3_3"  value="<?php echo ($db[8]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
          <tr class="row0">
            <td class="col1">二级：<br />
              No.1</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item1_4" id="item1_4"  value="<?php echo ($db[9]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_5" id="item1_5"  value="<?php echo ($db[10]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_6" id="item1_6"  value="<?php echo ($db[11]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item2_4" id="item2_4"  value="<?php echo ($db[12]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_5" id="item2_5"  value="<?php echo ($db[13]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_6" id="item2_6"  value="<?php echo ($db[14]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item3_4" id="item3_4"  value="<?php echo ($db[15]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_5" id="item3_5"  value="<?php echo ($db[16]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_6" id="item3_6"  value="<?php echo ($db[17]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
          <tr class="row0">
            <td class="col1">二级：<br />
              No.2</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item1_7" id="item1_7"  value="<?php echo ($db[18]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_8" id="item1_8"  value="<?php echo ($db[19]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_9" id="item1_9"  value="<?php echo ($db[20]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item2_7" id="item2_7"  value="<?php echo ($db[21]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_8" id="item2_8"  value="<?php echo ($db[22]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_9" id="item2_9"  value="<?php echo ($db[23]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item3_7" id="item3_7"  value="<?php echo ($db[24]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_8" id="item3_8"  value="<?php echo ($db[25]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_9" id="item3_9"  value="<?php echo ($db[26]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
          <tr class="row0">
            <td class="col1">二级：<br />
              No.3</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item1_10" id="item1_10"  value="<?php echo ($db[27]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_11" id="item1_11"  value="<?php echo ($db[28]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_12" id="item1_12"  value="<?php echo ($db[29]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item2_10" id="item2_10"  value="<?php echo ($db[30]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_11" id="item2_11"  value="<?php echo ($db[31]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_12" id="item2_12"  value="<?php echo ($db[32]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item3_10" id="item3_10"  value="<?php echo ($db[33]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_11" id="item3_11"  value="<?php echo ($db[34]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_12" id="item3_12"  value="<?php echo ($db[35]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
          <tr class="row0">
            <td class="col1">二级：<br />
              No.4</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item1_13" id="item1_13"  value="<?php echo ($db[36]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_14" id="item1_14"  value="<?php echo ($db[37]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_15" id="item1_15"  value="<?php echo ($db[38]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item2_13" id="item2_13"  value="<?php echo ($db[39]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_14" id="item2_14"  value="<?php echo ($db[40]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_15" id="item2_15"  value="<?php echo ($db[41]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item3_13" id="item3_13"  value="<?php echo ($db[42]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_14" id="item3_14"  value="<?php echo ($db[43]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_15" id="item3_15"  value="<?php echo ($db[44]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
          <tr class="row0">
            <td class="col1">二级：<br />
              No.5</td>
            <td ><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item1_16" id="item1_16"  value="<?php echo ($db[45]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item1_17" id="item1_17"  value="<?php echo ($db[46]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item1_18" id="item1_18"  value="<?php echo ($db[47]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item2_16" id="item2_16"  value="<?php echo ($db[48]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item2_17" id="item2_17"  value="<?php echo ($db[49]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item2_18" id="item2_18"  value="<?php echo ($db[50]); ?>" /></td>
                </tr>
              </table></td>
            <td><table  class="table">
                <tr>
                  <td>名称：</td>
                  <td><input type="text" maxlength="20" class="form-control" name="item3_16" id="item3_16"  value="<?php echo ($db[51]); ?>" /></td>
                </tr>
                <tr>
                  <td>Key：</td>
                  <td><input type="text" class="form-control" name="item3_17" id="item3_17"  value="<?php echo ($db[52]); ?>" /></td>
                </tr>
                <tr>
                  <td>Url： </td>
                  <td><input type="text" class="form-control" name="item3_18" id="item3_18"  value="<?php echo ($db[53]); ?>" /></td>
                </tr>
              </table></td>
          </tr>
        </table>
        <div class="form-group" >
          <div class="controls">
            <hr />
            <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
            <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>