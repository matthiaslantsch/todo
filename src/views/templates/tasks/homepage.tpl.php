<div class="container">
	<div class="well well-sm" style="margin-top: 3rem;">
		<h1 class="text-center">
			<?=$_sessionuser->name?> Todos
			<button onclick="loadTodos()" class="btn btn-link">reload</button>
		</h1>
	</div>
	<div id="table" data-showconfig='<?=json_encode($showconfig)?>'></div>
	<div class="row">
		<form id="addTaskForm">
			<div class="col-md-2">
				<input type="date" class="form-control" id="date" value="<?=date('Y-m-d')?>" required>
			</div>
			<div class="col-md-2">
				<input type="time" class="form-control form-inline" id="time" value="23:59" required>
			</div>
			<div class="col-md-5">
				<input type="text" class="form-control" id="description" required>
			</div>
			<div class="col-md-2">
				<select class="form-control" id="priority">
					<option value="1">Low</option>
					<option value="2">Medium</option>
					<option value="3" selected>High</option>
					<option value="4">Important</option>
					<option value="5">Critical</option>
				</select>
			</div>
			<div class="col-md-1">
				<button type="submit" class="btn btn-default">Add</button>
			</div>
		</form>
	</div>
</div>
