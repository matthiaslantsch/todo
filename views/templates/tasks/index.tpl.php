<div class="row text-center">
	<div class="btn-group" role="group" aria-label="...">
		<button class="btn btn-default btn-sm" onclick="changeBank(-1000)">-1000</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(-500)">-500</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(-200)">-200</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(-100)">-100</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(-50)">-50</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(-1)">-1</button>
		<button class="btn btn-default btn-lg" disabled><?=$bank->bank?>c</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+1)">+1</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+50)">+50</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+100)">+100</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+200)">+200</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+500)">+500</button>
		<button class="btn btn-default btn-sm" onclick="changeBank(+1000)">+1000</button>
	</div>
</div>
<hr>
<?php foreach($results as $title => $group): ?>
	<?php if(!empty($group)): ?>
		<h2><?=ucfirst($title)?></h2>
		<div class="list-group">
			<?php foreach($group as $task): ?>
				<div class="row list-group-item list-group-item-action list-group-item-<?=priorityColour($task->priority)?>">
					<div class="col-md-1">
						<div onchange="taskDone(<?=$task->id?>)" class="custom-control custom-checkbox mr-sm-2">
							<input type="checkbox" class="custom-control-input" <?=($task->donedate !== null ? "checked disabled" : "")?>>
						</div>
					</div>
					<div class="col-md-8"><?=($task->donedate !== null ? "<del>{$task->name}</del>" : "{$task->name}")?></div>
					<div class="col-md-2"><?=($task->donedate !== null ? "<del>{$task->duedate->format("db")}</del>" : "{$task->duedate->format("db")}")?></div>
					<div class="col-md-1">
						<button onclick="deleteTask(<?=$task->id?>)" class="btn-link">&times;</button>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php endforeach; ?>
<hr>
