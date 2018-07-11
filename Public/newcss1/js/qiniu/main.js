  $.ajax({url: "/member/uptoken",dataType:"json", success: function(res){
    var token = res.uptoken;
    var domain = res.domain;
    var config = {
      useCdnDomain: true,
      disableStatisticsReport: false,
      retryCount: 6,
      region: qiniu.region.z2
    };
    var putExtra = {
      fname: "",
      params: {},
      mimeType: null
    };
    uploadWithSDK(token, putExtra, config, domain);
  }})
