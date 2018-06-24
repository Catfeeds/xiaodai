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
</head>
<body>
<div class="row">
  <div class="col-md-12 " >
    <h2><?php echo ($title); ?></h2>
  </div>
  <div class="col-md-12 " >
    <form action="" method="post" name="form1" id="form1" class="ajaxformx">
      <div class="fancy-tab-container">
        <ul class="nav nav-tabs fancy">
          <?php if(is_array($group)): $i = 0; $__LIST__ = $group;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($key) == "1"): ?><li  class="active"><a href="#autotab_<?php echo ($i); ?>" data-toggle="tab"><?php echo ($vo); ?>配置</a></li>
              <?php else: ?>
              <li><a href="#autotab_<?php echo ($i); ?>" data-toggle="tab"><?php echo ($vo); ?>配置</a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="autotab_1">
            <div class="col-md-6">
              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i; if((1 == $config['group'])): ?><div class="form-group"> 
                    <!-- Text input-->
                    <label class="control-label" ><?php echo ($config["title"]); ?>：</label>
                    <div class="controls">
                      <?php switch($config["type"]): case "0": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "1": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "2": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "3": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
                            <?php $_result=parse_field_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" 
                              <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                              ><?php echo ($vo); ?>
                              </option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select><?php break; endswitch;?>
                      <p class="help-block">（<?php echo ($config["remark"]); ?>）</p>
                    </div>
                  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="tab-pane " id="autotab_2">
            <div class="col-md-6">
              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i; if((2 == $config['group'])): ?><div class="form-group"> 
                    <!-- Text input-->
                    <label class="control-label" ><?php echo ($config["title"]); ?>：</label>
                    <div class="controls">
                      <?php switch($config["type"]): case "0": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "1": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "2": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "3": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
                            <?php $_result=parse_field_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" 
                              <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                              ><?php echo ($vo); ?>
                              </option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select><?php break; endswitch;?>
                      <p class="help-block">（<?php echo ($config["remark"]); ?>）</p>
                    </div>
                  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="tab-pane " id="autotab_3">
            <div class="col-md-6">
              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i; if((3 == $config['group'])): if(($config["type"]) == "5"): else: ?>
                    <div class="form-group"> 
                      <!-- Text input-->
                      <label class="control-label" ><?php echo ($config["title"]); ?>：</label>
                      <div class="controls">
                        <?php switch($config["type"]): case "0": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                          <?php case "1": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                          <?php case "2": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                          <?php case "3": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                          <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
                              <?php $_result=parse_field_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" 
                                <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                                ><?php echo ($vo); ?>
                                </option><?php endforeach; endif; else: echo "" ;endif; ?>
                            </select><?php break; endswitch;?>
                        <p class="help-block">（<?php echo ($config["remark"]); ?>）</p>
                      </div>
                    </div><?php endif; endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clearfix"></div>
            
            
  <div class="hide">
      <div class="col-md-6">
              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i; if((0 == $config['group'])): ?><div class="form-group"> 
                    <!-- Text input-->
                    <label class="control-label" ><?php echo ($config["title"]); ?>：</label>
                    <div class="controls">
                      <?php switch($config["type"]): case "0": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "1": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "2": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "3": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
                            <?php $_result=parse_field_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>" 
                              <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                              ><?php echo ($vo); ?>
                              </option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select><?php break; endswitch;?>
                      <p class="help-block">（<?php echo ($config["remark"]); ?>）</p>
                    </div>
                  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
  </div>
  <div class="clr"></div>
  
  
          </div>

          <div class="tab-pane " id="autotab_4">
            <div class="col-md-6">
              <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$config): $mod = ($i % 2 );++$i; if((4 == $config['group'])): ?><div class="form-group">
                    <!-- Text input-->
                    <label class="control-label" ><?php echo ($config["title"]); ?>：</label>
                    <div class="controls">
                      <?php switch($config["type"]): case "0": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "1": ?><input type="text" class="form-control" name="config[<?php echo ($config["name"]); ?>]" value="<?php echo ($config["value"]); ?>" /><?php break;?>
                        <?php case "2": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "3": ?><textarea name="config[<?php echo ($config["name"]); ?>]"  class="form-control" rows="5"><?php echo ($config["value"]); ?></textarea><?php break;?>
                        <?php case "4": ?><select name="config[<?php echo ($config["name"]); ?>]" class="form-control">
                            <?php $_result=parse_field_attr($config['extra']);if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($key); ?>"
                              <?php if(($config["value"]) == $key): ?>selected<?php endif; ?>
                              ><?php echo ($vo); ?>
                              </option><?php endforeach; endif; else: echo "" ;endif; ?>
                          </select><?php break; endswitch;?>
                      <p class="help-block">（<?php echo ($config["remark"]); ?>）</p>
                    </div>
                  </div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
            </div>
            <div class="clearfix"></div>
          </div>

        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
        <div class="form-group" >
          <div class="controls">
            <hr />
            <button type="submit" class="btn btn-success" id="btnSubmit"><i class="fa fa-save"></i> 提交</button>
            <button type="button" class="btn btn-default" onClick="history.back();"><i class="fa fa-undo"></i> 返回</button>
          </div>
        </div>
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
</body>
</html>