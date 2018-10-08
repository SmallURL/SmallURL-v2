// Cheeky hack to make it so people cant scroll when its open.
$('#close_ticket_modal').on('show.bs.modal', function () {
  $('html').css('overflow','hidden');
})
$('#close_ticket_modal').on('hide.bs.modal', function () {
  $('html').css('overflow','visible');
})
function close_ticket(tick_id) {
	$.getJSON("/do/support/close_ticket.php?id="+tick_id,function(data) {
		if (data.res == true) {
			// All done. Refresh :D
			document.location.href = document.location.href;
		} else {
			alert(data.msg);
		}
	});
}