
function sendApikeyAndToken(btnId, devMode) {
    var btn = $("#" + btnId);
    var url = "/tenant/email/" + devMode + "/apikeyAndToken";
    
    btn.button('loading');

    $.ajax({
        url: url,
        type: 'GET',
        cache:false,
        dataType:'json',
        success:function(data){
            if (data.success) {
                alert(data.successMsg);
            } else {
                alert(data.errorMsg);
            }

            btn.button('reset');
        },
        error:function(jqXHR, textStatus, errorThrown){
            btn.button('reset');
            alert("发生错误,请稍后重试");
        }
    });
}
