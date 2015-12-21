$(function() {
	$.getJSON(host, function(data) {
		if (data.status == 'success') {
			console.log(data);
			render(data.data.ports);
		}
	});

	$('.port').click(function (e) {
		e.preventDefault();

		var el = $(this).parent(),
			portId = el.attr('data-port-id'),
			status = el.attr('data-status');

		$.ajax({
			url: host,
			method: 'PUT',
			data: {
				"port": portId,
				"status": parseInt(status) ? 'inactive' : 'active'
			},
			dataType: "json"
		}).done(function(data) {
			if (data.status == 'success') {
				console.log(data);
				render(data.data.ports);
			}
		}).fail(function(jqXHR, textStatus) {
			console.log("Request failed: " + textStatus);
		});
	});

	function render(ports) {
		$.each(ports, function (key, val) {
			var activeImg = 'http://dummyimage.com/200x200/dbdbdb/00cc00.png&text=X';
			var inactiveImg = 'http://dummyimage.com/200x200/dbdbdb/cc0000.png&text=O';
			var isActive = val.status == 'active';

			$('.port-' + key).attr('data-status', isActive ? 1 : 0);
			$('.port-' + key + ' img').attr('src', isActive ? activeImg : inactiveImg);
		});
	}
});
