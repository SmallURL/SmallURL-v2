function load_flagged() {
	// Load Flagged URLS!
	$.get("admin_do/flagged_list.php",function(data) {
		$('#flagged_urls').html(data);
		$.get("admin_do/flagged_count.php",function(data) {
			$('#flag_count').html(data);
		});
	});
}
function url_delete(urlid,callback) {
	$.post("admin_do/delete_url.php",{id:urlid},function (data) {
		var mydata = $.parseJSON(data);
		if (mydata.res == true) {
			// Worked
			eval(callback);
		}
		else {
			alert(mydata.msg);
		}
	});
}
function load_users(search) {
	// Load SmallIFYIERS!
	$.get("admin_do/user_list.php?q="+search,function(data) {
		$('#smallurl_users').html(data);
	});
}
function load_urls(search) {
	$.get("admin_do/url_list.php?q="+search,function(data) {
		$('#smallurl_urls').html(data);
	});
}