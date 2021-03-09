function loadTodos() {
	$.ajax({
		type: 'GET',
		data: {"query": $("#table").data("showconfig")},
		url: returnFWAlias() + "tasks",
		dataType: "html", //does not work in IE??
		success: function(res) {
			$("#table").html(res);
		},
		error: function() {
			location.reload();
		}
	});

}

function taskDone(idTask) {
	$.ajax({
		type: 'POST',
		data: {"done": true, "_method": "put"},
		url: returnFWAlias() + "tasks/"+idTask,
		dataType: "json", //does not work in IE??
		success: function(res) {
			loadTodos();
		},
		error: function() {
			location.reload();
		}
	});
}

function deleteTask(idTask) {
	$.ajax({
		type: 'POST',
		data: {"_method": "delete"},
		url: returnFWAlias() + "tasks/"+idTask,
		dataType: "json", //does not work in IE??
		success: function(res) {
			loadTodos();
		},
		error: function() {
			location.reload();
		}
	});
}

function changeBank(value) {
	$.ajax({
		type: 'POST',
		data: {
			"change": parseInt(value)
		},
		url: returnFWAlias() + "bank",
		dataType: "json",
		success: function(res) {
			if(res.errors !== false) {
				alert('Error changing the bank amount: '+res.errors.join());
			}
			loadTodos();
		},
		error: function() {
			location.reload();
		}
	});
}

$(function() {
	loadTodos();

	$("#addTaskForm").submit(function(e) {
		var form = $(this);
		e.preventDefault();

		$.ajax({
			type: 'POST',
			data: {
				"datetime": $("#date").val()+" "+$("#time").val(),
				"priority": $("#priority").val(),
				"description": $("#description").val()
			},
			url: returnFWAlias() + "tasks",
			dataType: "json",
			success: function(res) {
				if(res.errors !== false) {
					$.each(res.errors, function(key, arr) {
						alert('Error adding new task, field '+key+': '+(arr.join()));
					});
				}
				loadTodos();
			},
			error: function() {
				location.reload();
			}
		});
	});
});
