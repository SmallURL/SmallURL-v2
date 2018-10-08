/* SmallURL Javascript Core by Thomas Edwards
 * Copyright 2013-14 SmallURL
 * Do not attempt to exploit the SmallURL Service
*/
function generate_smallurl() {
	var url = encodeURIComponent($('#url_box').val());
	var api_url = "http://"+document.domain+"/api/?url="+url;
	// Check if someones trying to make a custom URL
	if (dashstat == 1) {
		var custom = $('#custom_box').val();
		if (custom != "") {
			var api_url = api_url.concat("&custom="+custom);
		}
	}
	// Check if the user would like the URL to be Private.
	var priv = ~~$('#private_box').prop('checked');
	if (priv) {
		var api_url = api_url.concat("&private=true");
	}
	// Call the API
	$.getJSON(api_url,function (data) {
		if (data.res == true) {
			$('#alert').hide(); // Just incase someone got an error last time.
			console.log('DEBUG: Got return');
			do_visuals(data);
		}
		else {
			$('#alert_error').html(data.msg);
			$('#alert').show();
		}
	});
}
function do_visuals(url) {
	console.log('Doing Visuals');
	refresh_stats();
	// 2014 Code!
	//var my_url = "http://"+document.domain+"/"+url.short;
	// Will be replaced with custom domains soon.
	var my_url = "http://surl.im/"+url.short;
	$('#url_box').val(my_url);
	if (dashstat) {
		collapse_dash();
	}
	$("#url_control_box").effect("shake");
	$('#url_box').css( "background-color", "#B6E1B3");
	$('#shorten_button').hide();
	$('#again_button').show();
}
function reset_form() {
	$('#again_button').hide();
	$('#shorten_button').show();
	$('#url_box').css( "background-color", "#FFF");
	if (dashstat) {
		collapse_dash();
	}
	$('#url_box').val('');
	$('#custom_box').val('');
}
function refresh_stats() {
	$.getJSON('api/json_stats.php',function (data) {
		$('.stats_total').html(data.total);
		$('.stats_random').html(data.random);
		$('.stats_custom').html(data.custom);
	});
	return true;
}
function validateText(str) {
	var tarea = str;
	if (tarea.indexOf("http://")==0 && tarea.indexOf("https://")==0) {
		return true;
	}
	else {
		return false;
	}
}
function show_screenshot() {
	$('#screenie').toggle();
}
// Key Stuff for the user accounts
function add_key() {
	var keyname = $('#keyname').val();
	var keydomain = $('#keydomain').val();
	$.post("/ajax/add_key.php",{name:keyname,domain:keydomain},function (data) {
		var mydata = $.parseJSON(data);
		if (mydata.res == true) {
			// Worked
			key_form();
		}
		else {
			alert(mydata.msg);
		}
	});
}
function del_key(keyid) {
	$.post("/ajax/del_key.php",{id:keyid},function (data) {
		var mydata = $.parseJSON(data);
		if (mydata.res == true) {
			// Worked
			key_form();
		}
		else {
			alert(mydata.msg);
		}
	});
}
function key_form() {
	$('#key_form').html("<h3 style='text-align:center;'>Loading your API Keys...</h3>");
	$.get('/ajax/key_form.php',function (data) {
		$('#key_form').html(data);
	});
}
// User Pages

function add_domain() {
	var pagename = $('#pagename').val();
	var pagedomain = $('#pagedomain').val();
	$.post("/api/add_domain.php",{name:pagename,domain:pagedomain},function (data) {
		var mydata = $.parseJSON(data);
		if (mydata.res == true) {
			// Worked
			domain_form();
		}
		else {
			alert(mydata.msg);
		}
	});
}
function del_domain(domainid) {
	$.post("/api/del_domain.php",{id:domainid},function (data) {
		var mydata = $.parseJSON(data);
		if (mydata.res == true) {
			// Worked
			domain_form();
		}
		else {
			alert(mydata.msg);
		}
	});
}

function domain_form() {
	$.get('/api/domain_form.php',function (data) {
		$('#domain_form').html(data);
	});
}
function save_prefs() {
	// turn on the Loading GIF
	$('#saving').show();
	$('#savebtn').text('Saving...');
	$('#savebtn').attr('disabled','true');
	var geoloc = ~~($('#geoloc').prop('checked'));
	var allsafe = ~~($('#allsafe').prop('checked'));
	var allpriv = ~~($('#allpriv').prop('checked'));
	var thm = $('#thm').val();

	$.post("http://"+document.domain+"/do/public/update_prefs.php",{'geoloc':geoloc,'allsafe':allsafe,'allpriv':allpriv,'thm':thm},function (data) {
		var data = $.parseJSON(data);
		if (data.res == true) {
			$('#savebtn').text('Saved!');
			$('#saving').hide();
		}
		else {
			alert(data.msg);
		}
	});
}
function change_prefs() {
	$('#savebtn').removeAttr('disabled');
	$('#savebtn').text('Save Changes');
}
function load_search_results(search,page) {
	// Load URLS!
	history.pushState({},"Search results for '"+search+"'","http://"+document.domain+"/search/"+search+"/"+page)
	$.get("http://"+document.domain+"/do/public/search.php?q="+search+"&i="+page,function(data) {
		$('#smallurl_search').html(data);
	});
}
function check_custom(word) {
	// Load URLS!
	$.getJSON("http://"+document.domain+"/do/public/check_custom?q="+search,function(data) {
		//$('#smallurl_search').html(data);
	});
}
function generate_custom() {
	var url = encodeURIComponent($('#url_box').val());
	if (url != '') {
		$.get('/do/public/generate.php?u='+url,function (data) {
			$('#custom_box').val(data);
		});
	}
	else {
		alert('Please enter a URL to generate a custom URL from!');
	}
}
function gen_qr() {
	var url = encodeURIComponent($('#short_url').val());
	if (url != '') {
		// Now we open the Lightbox with it in.
		$('#qr_img').attr('src','http://'+document.domain+'/do/public/gen_qr.php?u='+url);
		$('#qr_url').html($('#short_url').val());
		$('#qr_box').toggle();
	}
	else {
		alert('Please enter a URL to generate a custom URL from!');
	}
}
function check_status() {
	var url = encodeURIComponent($('#long_url').val());
	$.getJSON('/api/check_explicit.php?url='+url,function (data) {
		if (data.res) {
			// Its norty!
			$('#explicit_warning').show();
		} else {
			// Its okay.
			$('#explicit_warning').hide(); // Hide if its been shown.
		}
	});
}
$(document).ready(function(){
	dashstat = 0;
	$('.tip').tooltip({html:true});
});

function expand_dash() {
	dashstat = 1;
	$("#ineeda_hero").animate({ height: 500}, 1000);
	$("#ineeda_overlay").animate({ height: 500}, 1000);
	$("#dash_link").attr('onclick','collapse_dash(); return false;');
	$("#dash_text").html("<span style='font-size:12px;'>&#x25B2;&nbsp;&nbsp;&nbsp;</span> Collapse <span style='font-size:12px;'>&nbsp;&nbsp;&nbsp;&#x25B2;</span>");
}

function collapse_dash() {
	dashstat = 0;
	$("#ineeda_hero").animate({ height: 280}, 1000);
	$("#ineeda_overlay").animate({ height: 280}, 1000);
	$("#dash_link").attr('onclick','expand_dash(); return false;');
	$("#dash_text").html("<span style='font-size:12px;'>&#x25BC;&nbsp;&nbsp;&nbsp;</span> Customize <span style='font-size:12px;'>&nbsp;&nbsp;&nbsp;&#x25BC;</span>");
}

var NavStatus = '0';
function ToggleNav() {
    if(NavStatus == '0') {
        NavStatus = '1';
        $( "#dropdownnav" ).slideDown( "slow" );
    } else {
        NavStatus = '0';
        $( "#dropdownnav" ).slideUp( "slow" );
    }
}
function bounce_thomas() {
    $("#thomas").effect( "bounce",
          {times:3}, 900 );
}
function report_url(modal_id,surl) {
	var text = $('#report_text').val();
	$.post("/ajax/report_url.php?short="+surl,{"reason":text},function(data){
		var data = jQuery.parseJSON(data);
		if (data.res) {
			alert(data.msg);
			$('#'+modal_id).modal('hide');
			$('#report_text').val('');
			$('#len').html('');
		} else {
			alert(data.msg);
		}
	});
}
function report_user(modal_id,user) {
	var text = $('#report_text').val();
	$.post("/ajax/report_user.php?id="+user,{"reason":text},function(data){
		var data = jQuery.parseJSON(data);
		if (data.res) {
			alert(data.msg);
			$('#'+modal_id).modal('hide');
			$('#report_text').val('');
			$('#len').html('');
		} else {
			alert(data.msg);
		}
	});
}
function notif_read(notid) {
	$.getJSON("ajax/mark_notif.php?id="+notid,function(data){
		if(data.res) {
			if (data.unread > 0) {
				$("#none-alert").css('display','inline');
				$("#aa-notifications").css('display','block');
			} else {
				$("#none-alert").css('display','none');
				$("#aa-notifications").css('display','none');
			}
			$(".notif-count").each(function() {
				$(this).text(data.unread);
			});
			$("#notification-"+notid).slideUp('slow');
		} else {
			alert("It's broke!");
		}
	});
}
function notif_delete(notid) {

}

function regen_tokens(aid) {
	if (confirm("Regenerate Application Tokens?")) {
		$.getJSON("/do/apps/regen_token.php?appid="+aid,function(data){
			if(data.res) {
				// Worked.
				$("#pub-token").val(data.pub);
				$("#priv-token").val(data.priv);
			} else {
				alert(data.msg);
			}
		});
	}
}

function load_page(target,api,page) {
	$.get(api+"?page="+page,function(data){
		console.log(data);
		$(target).html(data);
	});
}