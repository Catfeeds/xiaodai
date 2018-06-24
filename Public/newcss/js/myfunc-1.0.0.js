/**
 * Created by YJ on 2018/3/27.
 *
 * 调用方法
 * 1：引用该js
 * 2：toast调用：yjfunc.mytoast(str,speed);//str:需要提示的文字内容；speed:提示框消失的速度，默认为3000毫秒（3秒）单位为：毫秒；
 * 3：confirm调用：
 * 1）：yjfunc.confirm('确认执行操作？',['否','是'],function(e){
 *         alert(e);e为点击 "是，否" 的 index---  0  or  1；
 *  });
 * 2):yjfunc.confirm('确认执行操作？',['我知道啦'],function(e){
 *         alert(e);返回的e为0；
 *  });
 * 3):yjfunc.confirm('确认执行操作？',function(e){
 *         alert(e);返回的e为0；
 *  });
 *
 *
 * 4:prompt 调用：yjfunc.myprompt("输入理由",['算了','写好了'], function (e) {if(e.index==1){alert(e.val);}});
 *
 *
 *
 */

var yjfunc ={
    id:"myboxid",
    ret:[],
    mytoast : function (e, s) {
        var html = " <div id='"+yjfunc.id+"' style=\"position: fixed;z-index:99999;display:none;bottom:200px;width: 100%;text-align: center;\">" +
            "<span style=\"background-color: rgba(0,0,0,0.7);padding:2px 5px;border-radius: 5px;color: white;\">" +
            e + "</span></div>";
        $("body").append(html);
        $("#"+yjfunc.id).fadeIn("normal");
        if (parseInt(s) > 0) {
            setTimeout(function () {
                $("#"+yjfunc.id).fadeOut("normal", yjfunc.toatremove);
            }, s);
        } else {
            setTimeout(function () {
                $("#"+yjfunc.id).fadeOut("normal", yjfunc.toatremove);
            }, 2000);
        }
    },

    myconfirm: function (a,b,e) {
        var html="<div id='"+yjfunc.id+"' style=\"position: fixed;z-index:9999; top: 0;padding-top:200px; width: 100%;background-color: rgba(0,0,0,0.5);height: 100%;\"> " +
            "<div style=\"width: 70%;margin: 0 auto;background: #fff;border-radius: 4px;border: 4px solid rgba(149, 149, 149, 0.5);box-sizing: border-box;\"> <div style=\"padding:30px 12px;border-bottom: 1px solid #ccc;box-sizing: border-box;\">"+
            a+"</div><div class=\"buttonbox\" style=\"overflow: hidden;\"> " ;

        if(typeof b!='undefined' && typeof b!='function'){
            if(b.length==1 ){
                html+="<p style=\"width:100%;float: left; text-align: center;padding: 12px 0;\">" +
                    "<button id='"+b[0]+"' style=\"background: #418bca; border: none;" +
                    "padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">"+b[0]+"</button> </p> </div> </div> </div>";
            }
            else{
                html+="<p style=\"width:50%;float: left; text-align: center;padding: 12px 0;\">" +
                    "<button id='"+b[0]+"' style=\"background: #418bca;float: right; margin-right: 20px;border: none;padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">"+b[0]+"</button> </p> " +
                    "<p style=\"width:50%;float: left;text-align: center; padding: 12px 0;\">" +
                    "<button  id='"+b[1]+"' style=\"background: #db534d;float: left; margin-left: 20px;border: none;padding: 8px 12px; border-radius: 4px;color: #fff;display: inline-block;\">"+b[1]+"</button>  </p> </div> </div> </div>";

            }
        }

        if(typeof b=='undefined' || typeof b=='function'){
            html+="<p style=\"width:100%;float: left; text-align: center;padding: 12px 0;\">" +
                "<button id='mycancle' style=\"background: #418bca; border: none;" +
                "padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">确定</button> </p></div> </div> </div> ";
        }



        $("body").append(html);
        if(typeof b!='undefined' && typeof b!='function'){
            if(b.length==1 ){
                $("#"+b[0]).attr("onclick", "yjfunc.btn_event.go(0)");//alert(yjfunc.btn_event.index)
            }else{
                $("#"+b[0]).attr("onclick", "yjfunc.btn_event.go(0)");//alert(yjfunc.btn_event.index)
                $("#"+b[1]).attr("onclick", "yjfunc.btn_event.go(1)");
            }
        }
        if(typeof b=='undefined' || typeof b=='function'){
            $("#mycancle").attr("onclick", "yjfunc.btn_event.go(0)");//alert(yjfunc.btn_event.index)
        }
        if(typeof b=='function'){
            yjfunc.btn_event.event=b;
        }
        if(typeof  e=='function'){
            yjfunc.btn_event.event=e;
        }
    },

    btn_event: {
        event: function (index){
        },
        go: function (index) {
            this.event(index);
            yjfunc.toatremove();
        }
    },

    prompt_event: {
        event: function (index){
        },
        go: function (index) {
            yjfunc.ret['index']=index;
            yjfunc.ret['val']=$("#myprompttext").val();
            this.event(yjfunc.ret);
            yjfunc.toatremove();
        }
    },


    myprompt: function (a,b,e) {
        var html="<div id='"+yjfunc.id+"' style=\"position: fixed;z-index:9999; top: 0;padding-top:200px; width: 100%;background-color: rgba(0,0,0,0.5);height: 100%;\"> " +
            "<div style=\"width: 70%;margin: 0 auto;background: #fff;border-radius: 4px;border: 4px solid rgba(149, 149, 149, 0.5);box-sizing: border-box;\"> " +
            "<div style=\"padding:30px 12px;border-bottom: 1px solid #ccc;box-sizing: border-box;\">" +
            "<textarea style='width: 100%;border-radius: 3px; padding-left: 3px;' rows='3' id='myprompttext' placeholder='"+a+"'></textarea>" +
            "</div><div class=\"buttonbox\" style=\"overflow: hidden;\"> " ;

        if(typeof b!='undefined' && typeof b!='function'){
            if(b.length==1 ){
                html+="<p style=\"width:100%;float: left; text-align: center;padding: 12px 0;\">" +
                    "<button id='"+b[0]+"' style=\"background: #418bca; border: none;" +
                    "padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">"+b[0]+"</button> </p> </div> </div> </div>";
            }
            else{
                html+="<p style=\"width:50%;float: left; text-align: center;padding: 12px 0;\">" +
                    "<button id='"+b[0]+"' style=\"background: #418bca;float: right; margin-right: 20px;border: none;padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">"+b[0]+"</button> </p> " +
                    "<p style=\"width:50%;float: left;text-align: center; padding: 12px 0;\">" +
                    "<button  id='"+b[1]+"' style=\"background: #db534d;float: left; margin-left: 20px;border: none;padding: 8px 12px; border-radius: 4px;color: #fff;display: inline-block;\">"+b[1]+"</button>  </p> </div> </div> </div>";

            }
        }
        if(typeof b=='undefined' || typeof b=='function'){
            html+="<p style=\"width:100%;float: left; text-align: center;padding: 12px 0;\">" +
                "<button id='mycancle' style=\"background: #418bca; border: none;" +
                "padding: 8px 12px;border-radius: 4px;color: #fff;display: inline-block;\">确定</button> </p></div> </div> </div> ";
        }

        $("body").append(html);

        if(typeof b!='undefined' && typeof b!='function'){
            if(b.length==1 ){
                $("#"+b[0]).attr("onclick", "yjfunc.prompt_event.go(1)");//alert(yjfunc.btn_event.index)
            }else{
                $("#"+b[0]).attr("onclick", "yjfunc.prompt_event.go(0)");//alert(yjfunc.btn_event.index)
                $("#"+b[1]).attr("onclick", "yjfunc.prompt_event.go(1)");
            }
        }
        if(typeof b=='undefined' || typeof b=='function'){
            $("#mycancle").attr("onclick", "yjfunc.prompt_event.go(1)");//alert(yjfunc.btn_event.index)
        }
        if(typeof b=='function'){
            yjfunc.prompt_event.event=b;
        }
        if(typeof  e=='function'){
            yjfunc.prompt_event.event=e;
        }
    },


    toatremove : function () {
        $("#"+yjfunc.id).remove();
    }
};


