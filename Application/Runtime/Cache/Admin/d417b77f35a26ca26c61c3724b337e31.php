<?php if (!defined('THINK_PATH')) exit(); if(is_array($tree)): $i = 0; $__LIST__ = $tree;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
    <td><?php echo ($list["id"]); ?></td>
    <td><?php echo str_repeat("&nbsp;",($list['depth']-1)*4);?>|- <?php echo ($list["name"]); ?></td>
    <td><?php echo (cut_str($list["remark"])); ?></td>
    <td><input name="Item_<?php echo ($list["id"]); ?>" id="Item_<?php echo ($list["id"]); ?>" onchange="setVal('<?php echo ($tblname); ?>','sort',<?php echo ($list["id"]); ?>,$(this).val())" class="form-control w80" value="<?php echo ($list["sort"]); ?>" /></td>
    <td>
    <?php if(($list["status"]) == "1"): ?><a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($list["id"]); ?>,0,this,'')" class="btn btn-link" rel="tooltip" data-original-title="点击<?php echo ($statuslist["1"]); ?>"><i class="fa fa-check-circle"></i> <?php echo ($statuslist["1"]); ?></a>
        <?php else: ?>
        <a href="javascript:void(0);" onclick="setVal('<?php echo ($tblname); ?>','status',<?php echo ($list["id"]); ?>,1,this,'')" class="btn btn-link fc_red"  rel="tooltip" data-original-title="点击<?php echo ($statuslist["1"]); ?>"><i class="fa fa-times-circle"></i> <?php echo ($statuslist["0"]); ?></a><?php endif; ?>
       </td>
    <td>
    <a href="<?php echo U($control.'/add'.$action,'pid='.$list['id']);?>" class="btn" rel="tooltip" data-original-title="添加"><i class="fa fa-plus"></i></a> 
    <a href="<?php echo U($control.'/edit'.$action,'id='.$list['id']);?>" class="btn" rel="tooltip" data-original-title="修改"><i class="fa fa-edit"></i></a> 
    <a   href="javascript:void(0);" onclick="setDelete('<?php echo U("Setting/delete".$action,"id=".$list['id']);?>')" class="btn" rel="tooltip" data-original-title="删除"><i class="fa fa-trash"></i></a>
    </td>
  </tr>
  <?php if(!empty($list['_child'])): echo R($control.'/treelist', array($list['_child'])); endif; endforeach; endif; else: echo "" ;endif; ?>