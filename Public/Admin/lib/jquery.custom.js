//增强插件
ADMIN_PATH="/Admin";
(function() {
	$.rendAjaxForm = function($form) {
		var $obj=$("button[type='submit']");
		$form.ajaxForm({
			beforeSubmit:function(){
				$obj.find(".fa").removeClass("fa-save").addClass("fa-refresh fa-spin");

			},
			success: function(data) {
				if (data.status == 1) {
					var url=$("#gourl").val();
					if(url){
						alertok(data.info);
						location=url;
					}else{
						alertok(data.info);
						reloadwin();
					}
				} else {
					alerterr(data.info);
					$obj.attr("disabled", false);
				}
				$obj.find(".fa").addClass("fa-save").removeClass("fa-refresh fa-spin");
			}
		});
	};
})(jQuery);

//执行：必须
$(function() {
	
	//模态框按钮
	$(".Custom-search").click(function(){
		var $type="member";
		var $id=$(this).attr("id");
		var $did=$(this).attr("data-id");
		var $val=$("#"+$did).val();
		
		var $url=ADMIN_PATH + "/Content/modal?type="+$type+"&val="+$val+"&id="+$id;
		jBox('<iframe src="'+$url+'" width="740" height="400" frameborder="0" />',"选择");
		
	
	});


	//模态框按钮
	$(".member_search").click(function(){
		var $memberid=$(this).attr("data-id");

		var $url=ADMIN_PATH + "/Crm/applymember?memberid="+$memberid;
		jBox('<iframe src="'+$url+'" width="740" height="400" frameborder="0" />',"私有客户申请");
	});

	//模态框按钮
	$(".article-search").click(function(){
		var $articleid=$(this).attr("data-id");

		var $url=ADMIN_PATH + "/Finance/articlemember?id="+$articleid;
		jBox('<iframe src="'+$url+'" width="740" height="400" frameborder="0" />',"单篇文章传播排名");
	});

		//模态框按钮
	$(".detail-search").click(function(){
		var $articleid=$(this).attr("data-id");
		var $name=$(this).attr("data-name");

		var $url=ADMIN_PATH + "/Finance/articledetail?id="+$articleid+"&name="+$name;
		jBox('<iframe src="'+$url+'" width="740" height="400" frameborder="0" />',"单篇文章浏览分享点赞详细");
	});

	
	
	//标题提示
	$("[rel=tooltip]").tooltip({
		animation: false
	});
	
	//全选
	$("#AllCheck").click(function() {
		$(".custom-table input[name='selectids']").prop("checked", true);
	});
	
	//反选
	$("#ReverseCheck").click(function() {
		$(".custom-table input[name='selectids']").each(function() {
			$(this).prop("checked", !$(this).prop("checked"));
		});
	});
	
	//批量改变状态
	$(".AllStatus").click(function() {
		var ids = getSelectIDs();
		if (ids == "") {
			alerterr("请您先勾选要操作条目！");
		} else {
			var tbl = $("#ConstTbl").val();
			var id =  $(this).attr("data-status");

			bootbox.confirm("您确定要批量设置选中条目吗?", function(result) {
				if (result) {
					$.ajax({
						url: ADMIN_PATH + "/Rbac/batch?table=" + tbl + "&id=" + ids + "&col=__sta__&v=" + id + "&" + Math.random(),
						success: function(msg) {
							msg = eval(msg);
							if (msg.status == "1") {
								alertok("批量设置成功！");
								reloadwin();
							} else {

							}

						}
					});
				};
			});
		};
	});
	
	//批量删除
	$("#AllDel").click(function() {
		var ids = getSelectIDs();
		if (ids == "") {
			alerterr("请您先勾选要操作条目！");
		} else {
			var tbl = $("#ConstTbl").val();
			bootbox.confirm("您确定要批量删除这些条目吗?", function(result) {
				if (result) {

					$.ajax({
						url: ADMIN_PATH + "/Rbac/batch?table=" + tbl + "&id=" + ids + "&col=__del__&v=" + Math.random(),
						success: function(msg) {
							msg = eval(msg);
							if (msg.status == "1") {
								alertok("批量删除成功！");
								reloadwin();
							} else {

							}

						}
					});
				};
			});
		};
	});

});

//重新加载当前页面
function reloadwin() {
	setTimeout(function() {
		location.reload()
	}, 500);
}


//设置文本值
function setVal(tbl, col, id, val) {
	$.ajax({
		url: ADMIN_PATH + "/Rbac/batch?table=" + tbl + "&id=" + id + "&col=" + col + "&v=" + val + "&" + Math.random(),
		success: function(msg) {
			msg = eval(msg);
			if (msg.status == "1") {
				var str = col.substring(0, 3).toLowerCase();
				if (str == "pri" || str == "sor") {} else {
					location.reload();
				}
			} else {

			}
		}
	});
}
//确认？删除
function setDelete($url) {
	bootbox.confirm("您确定删除该记录吗?", function(result) {
		if (result) {
			$.ajax({
				url: $url,
				success: function(msg) {
					if (msg.status == "1") {
						location.reload();
					} else {
						alerterr(msg.info);
					}
				}
			});
		}
	})
}

//省市县三级联动
function InitArea() {
	var url = ADMIN_PATH + "/Login/getArea";


	$("#China_Province").change(function() {
		$("#China_City,#China_District,#China_Street").hide();
		var url1 = url + "?tbl=china_city&id=" + $(this).val() + "&" + Math.random();
		$.ajax({
			url: url1,
			success: function(msg) {
				$("#China_City").html(msg);
				$("#China_City").show()
			}
		})
	});
	$("#China_City").change(function() {
		$("#China_District,#China_Street").hide();
		var url1 = url + "?tbl=china_district&id=" + $(this).val() + "&" + Math.random();
		$.ajax({
			url: url1,
			success: function(msg) {
				$("#China_District").html(msg);
				$("#China_District").show()
			}
		})
	})
	$("#China_District").change(function() {
		$("#China_Street").hide();
		var url1 = url + "?tbl=china_street&id=" + $(this).val() + "&" + Math.random();
		$.ajax({
			url: url1,
			success: function(msg) {
				$("#China_Street").html(msg);
				if (msg == "") {
					$("#China_Street").hide()
				} else {
					$("#China_Street").show()
				}
			}
		})
	})
};

//获取选中id
function getSelectIDs() {
	var selectids = "";
	$(".custom-table input:checked[name='selectids']").each(function() {
		selectids += $(this).val() + ",";
	});
	return selectids;
};

//提示：友好
function alerttip($msg) {
	alertok('<i class="icon-exclamation-sign"></i> ' + $msg, 'danger');
}
//提示：错误
function alerterr($msg) {
	alertok('<i class="icon-remove-sign"></i> ' + $msg, 'error');
}
//提示：正确
function alertok($msg) {
	var $css = 'success';
	if (arguments[1] != undefined) {
		$css = arguments[1];
	} else {
		$msg = '<i class="icon-ok-sign"></i> ' + $msg;
	}
	var id = "#sysnotify";
	if ($(id).length == 0) {
		$("body").append("<div class='notifications top-right' id='sysnotify'></div>");
	}
	$(id).notify({
		message: {
			html: $msg
		},
		type: $css
	}).show();
}

function jBox($url,$title){
	var callback=$.isFunction(arguments[2])?arguments[2]:function(){}; 
	 bootbox.dialog({
       title: $title,
       className:'modal-iframe',
	   onEscape:callback,
       message: $url/*,
        buttons: {
           success: {
             label: "关闭",
             className: "btn",
             callback: function() {
               alertok("关闭");
             }
           }
         }*/
     });
}