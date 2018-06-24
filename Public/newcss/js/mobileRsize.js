//	var window_width = 750;
var window_width = 414;
var window_size = 115;
(function() {
	$.fn.autoSize = function(options) {
		options = $.extend({}, $.fn.autoSize.defaults, options || {});
		autoSet();
		$(window).resize(function() {
			autoSet();
		});

		function autoSet() {
			var width = $(this).width();
			var newsize = $.fn.autoSize.method.getFS(options.designWith, options.designFS, width);
			$.fn.autoSize.method.setFS(options.target, newsize);
		}
	};
	$.fn.autoSize.method = {
		getFS: function(designWith, designFs, winWith) { //fontsize
			return winWith / designWith * 115;
		},
		setFS: function(target, FS) {
			target.style.fontSize = FS + "px";
		}
	};
	$.fn.autoSize.defaults = {
		designWith: 414,
		//	        designWith : 750,
		designFS: 115,
		target: document.documentElement
	};
})(jQuery);
$(window).autoSize({
	designWidth: window_width,
	designFS: window_size
});