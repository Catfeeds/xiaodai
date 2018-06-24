mui.ready(function() {
	mui('body').on('tap','a',function(e){
		if(!this.getAttribute('href')==''){
			mui.openWindow({
				url: this.getAttribute('href'),			
			});	
		}
	});
	
});