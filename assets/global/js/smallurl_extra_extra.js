var smallurl_extra_extra_extra_extra = new Konami();
smallurl_extra_extra_extra_extra.code = function() { smallurl_extra_extra_extra(); }
smallurl_extra_extra_extra_extra.load();
function smallurl_extra_extra_extra() {
	$('html').css('overflow','hidden');
	$('body').append('<iframe id="pong" src="http://tmfksoft.x10.mx/jtests/pong/" style="width:100%;height:100%;background:black;position:fixed;top:0;left:0;z-index:999999;border:none;"></iframe>');
}