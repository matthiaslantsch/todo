		</script><script src='https://code.jquery.com/jquery-2.2.4.min.js'></script>

		<script >$(document).ready(function () {

			$("#add-button").click(function () {
				if ($("#task").val().trim() != '') {
					$("#table").prepend("<tr><td>" + $("#task").val() + "</td><td>" + $("#priority").val() + "</td><td><button type='button' id='remove-button' class='btn btn-default'>Remove</button></td></tr>");
					$('#task').val('');
				} else {
					alert("You can't do *nothing* as a task!");
				}
			});

			$(document).on('click', '#remove-button', function () {
				$(this).parent().parent().remove();
			});

		});

		</script>
		<script src="<?=$_urlhelper->linkJs("todo")?>"></script>
	</body>
</html>
