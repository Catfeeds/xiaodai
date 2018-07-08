function uploadWithSDK(token, putExtra, config, domain) {
  // 切换tab后进行一些css操作
  $(".uploadpic").unbind("change").bind("change",function(){
    var classes = $(this).attr('data');
    var file = this.files[0];
    // eslint-disable-next-line
    var finishedAttr = [];
    // eslint-disable-next-line
    var compareChunks = [];
    var observable;
    if (file) {
      var filename = file.name;
      var ext =/\.[^\.]+/.exec(filename);
      var key = domain+$.md5(domain+file.name)+ext;
      putExtra.params["x:name"] = filename.split(".")[0];
      // 设置next,error,complete对应的操作，分别处理相应的进度信息，错误信息，以及完成后的操作
      var error = function(err) {
        console.log(err);
        alert("上传出错,请重新上传！")
      };
      var complete = function(res) {
        if (res.key && res.key.match(/\.(jpg|jpeg|png|gif)$/)) {
            saveimg(filename,key,classes);
        }
      };

      var next = function(response) {

        var chunks = response.chunks||[];
        var total = response.total;

        // 这里对每个chunk更新进度，并记录已经更新好的避免重复更新，同时对未开始更新的跳过
        for (var i = 0; i < chunks.length; i++) {
          if (chunks[i].percent === 0 || finishedAttr[i]){
            continue;
          }
          if (compareChunks[i].percent === chunks[i].percent){
            continue;
          }
          if (chunks[i].percent === 100){
            finishedAttr[i] = true;
          }
        }

        compareChunks = chunks;
      };

      var subObject = { 
        next: next,
        error: error,
        complete: complete
      };
      // 调用sdk上传接口获得相应的observable，控制上传和暂停
      observable = qiniu.upload(file, key, token, putExtra, config);
      var subscription = observable.subscribe(subObject);
    }
  })

    function saveimg(filename,key,classes){
        $.ajax({
            url:"/Member/saveqiniuimg.html",
            data:{filename:filename,key:key},
            type:"POST",
            success: function (data) {
                if(data.status==1){
                    var html="<img class=\"idimg\" style=\"width:100%;height:100%\" src=\""+data.info+"\"/>" ;
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
    }
}
