$(function() {
	$.getJSON(host, function(data) {
		if (data.status == 'success') {
			console.log(data);
			render(data.data.ports);
		}
	});

	$('.port').click(function (e) {
		e.preventDefault();

		var passiveImg = 'http://dummyimage.com/200x200/dbdbdb/888888.png&text=UPDATING';
		var el = $(this).parent(),
			portId = el.attr('data-port-id'),
			status = el.attr('data-status');

		$(this).find('img').attr('src', passiveImg);

		$.ajax({
			url: host,
			method: 'PUT',
			data: {
				"port": portId,
				"status": parseInt(status) ? 'inactive' : 'active'
			},
			dataType: "json"
		}).done(function(data) {
			var renderData = {};
			renderData[portId] = {
				status: parseInt(status) ? 'inactive' : 'active'
			};

			if (data.status == 'success') {
				render(renderData);
			}
		}).fail(function(jqXHR, textStatus) {
			console.log("Request failed: " + textStatus);
		});
	});

	function render(ports) {
		console.log(ports);

		$.each(ports, function (key, val) {
			var activeImg = 'http://dummyimage.com/200x200/dbdbdb/00cc00.png&text=X';
			var inactiveImg = 'http://dummyimage.com/200x200/dbdbdb/cc0000.png&text=O';
			var isActive = val.status == 'active';

			$('.port-' + key).attr('data-status', isActive ? 1 : 0);
			$('.port-' + key + ' img').attr('src', isActive ? activeImg : inactiveImg);
		});
	}
});
