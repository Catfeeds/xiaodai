// 实现form直传无刷新并解决跨域问题
function uploadWithForm(token, putExtra, config,domain) {
  // 获得上传地址
  qiniu.getUploadUrl(config, token).then(function(res){
    var uploadUrl = res;
    document.getElementsByName("token")[0].value = token;
    document.getElementsByName("url")[0].value = uploadUrl;
    // 当选择文件后执行的操作


      $(".uploadpic").unbind("change").bind("change",function(){
          window.showRes = function(res){
              $(document)
                  .find(".control-container")
                  .html(
                      "<p><strong>Hash：</strong>" +
                      res.hash +
                      "</p>" +
                      "<p><strong>Bucket：</strong>" +
                      res.bucket +
                      "</p>"
                  );
          }
          var iframe = createIframe();
          var file = this.files[0];
          var ext =/\.[^\.]+/.exec(file.name);
          var key = domain+$.md5(domain+file.name)+ext;
          document.getElementsByName("key")[0].value = key;
          $("#uploadForm").attr("target", iframe.name);
          $("#uploadForm").attr("action", "/api/transfer").submit();
      })
    $(".uploadpic").unbind("change").bind("change",function(){
        window.showRes = function(res){
            $(document)
                .find(".control-container")
                .html(
                    "<p><strong>Hash：</strong>" +
                    res.hash +
                    "</p>" +
                    "<p><strong>Bucket：</strong>" +
                    res.bucket +
                    "</p>"
                );
        }
      var iframe = createIframe();
      var file = this.files[0];
      var ext =/\.[^\.]+/.exec(file.name);
      var key = domain+$.md5(domain+file.name)+ext;
      document.getElementsByName("key")[0].value = key;
      $("#uploadForm").attr("target", iframe.name);
      $("#uploadForm").attr("action", "/api/transfer").submit();
    })
  });
}

function createIframe() {
  var iframe = document.createElement("iframe");
  iframe.name = "iframe" + Math.random();
  $("#directForm").append(iframe);
  iframe.style.display = "none";
  return iframe;
}


