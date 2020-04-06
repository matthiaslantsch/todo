<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\controllers;

use DateTime;
use RuntimeException;
use holonet\holofw\FWController;
use holonet\holofw\session\User;
use holonet\todo\helpers\TaskList;
use holonet\todo\models\BankModel;
use holonet\todo\models\TaskModel;
use holonet\todo\models\UserModel;

/**
 * TasksController exposing the task REST resource to the application user.
 */
class TasksController extends FWController {
	/**
	 * @var UserModel $user The model for the user that is using the application
	 */
	private $user;

	/**
	 * Authenticate the user first and save his reference in here.
	 */
	public function __before(): void {
		if (!$this->authoriseUser()) {
			return;
		}
		/** @var User $sessionUser */
		$sessionUser = $this->session()->get('user');
		/**
		 * @psalm-suppress PossiblyNullArgument
		 * @var UserModel
		 */
		$this->user = $this->di_repo->find(UserModel::class, $sessionUser->internalid);
	}

	/**
	 * POST /bank
	 * method used to update the reward bank of a user.
	 */
	public function bankChange(): void {
		$bank = $this->user->bank;
		$bank->bank += (int)($this->request->request->get('change'));
		$this->view->set('errors', !$bank->save());
		$this->respondTo('json');
	}

	/**
	 * POST /tasks
	 * method used to create a new task.
	 */
	public function create(): void {
		$rawdata = array(
			'datetime' => $this->request->request->get('datetime', new DateTime()),
			'priority' => $this->request->request->getInt('priority', 1),
			'description' => $this->request->request->get('description', ''),
		);

		if ($rawdata['description'] !== '') {
			preg_match('/^(.+?) ?(?:\\[(\\d+)\\]|)$/', strip_tags($rawdata['description']), $matches);
			$rawdata['description'] = $matches[1];
		}

		$data = array(
			'name' => $rawdata['description'],
			'donereward' => $matches[2] ?? 0,
			'priority' => $rawdata['priority'],
			'duedate' => $rawdata['datetime'],
			'user' => $this->user
		);

		$task = $this->di_repo->new(TaskModel::class, $data);

		if (($validResult = $task->valid()) === true) {
			if (!$task->save()) {
				throw new RuntimeException('Could not save new task valid?:'.var_export($task->valid(), true));
			}

			$this->view->set('errors', false);
		} else {
			$this->view->set('errors', $validResult->getAll());
		}

		$this->respondTo('json');
	}

	/**
	 * DELETE /tasks/[{$id}:i]
	 * method used to delete a task entry.
	 * @param int $id The id of the task to be deleted
	 */
	public function delete(int $id): void {
		if (($task = $this->di_repo->find(TaskModel::class, $id)) === null) {
			$this->notFound("task with the id '#{$id}'");

			return;
		}

		if ($task->idUser !== $this->user->id) {
			$this->notAllowed(
				"User with id '{$this->user->id}' is not allowed to change/delete other users' tasks"
			);

			return;
		}

		$this->view->set('errors', $task->delete());
		$this->respondTo('json');
	}

	/**
	 * ANY / (root homepage).
	 */
	public function homepage(): void {
		$this->view->set('title', "Todos for {$this->user->username}");
		$date = new DateTime();
		//check if it's weekend
		if ($date->format('N') >= 6) {
			//only show the weekend todos (and important / critical)
			$this->view->set('showconfig', array(
				'critical' => 5,
				'important' => 4,
				'weekend' => array(
					'from' => date('Y-m-d H:i:s', strtotime('saturday')),
					//go to end of sunday => monday midnight
					'to' => date('Y-m-d H:i:s', strtotime('monday')),
				)
			));
		} else {
			//default display style is a week showing every day except weekend in one
			//also show important / critical
			$ranges = array('critical' => 5, 'important' => 4);
			$day = new DateTime('monday this week');
			while (($wday = $day->format('l')) !== 'Saturday') {

				/** @psalm-suppress PossiblyInvalidArrayAssignment */
				$ranges[$wday]['from'] = $day->format('Y-m-d H:i:s');
				$day->modify('+1 day');

				/** @psalm-suppress PossiblyInvalidArrayAssignment */
				$ranges[$wday]['to'] = $day->format('Y-m-d H:i:s');
			}

			/** @psalm-suppress PossiblyInvalidArrayAssignment */
			$ranges['weekend']['from'] = $day->format('Y-m-d H:i:s');
			$day->modify('+2 days');
			/** @psalm-suppress PossiblyInvalidArrayAssignment */
			$ranges['weekend']['to'] = $day->format('Y-m-d H:i:s');
			//make it a sequential array to keep the order
			$this->view->set('showconfig', $ranges);
		}
	}

	/**
	 * POST /tasks/index
	 * Display an index listing via json / html.
	 */
	public function index(): void {
		$this->view->set('bank', $this->di_repo->getOrCreate(BankModel::class, array('user' => $this->user)));

		if (!$this->request->request->has('query')) {
			$this->notFound('No search query definition was submitted');

			return;
		}

		$query = $this->request->request->get('query');
		$search = new TaskList($this->di_repo, $this->user->id);
		foreach ($query as $name => $range) {
			if (is_array($range) && count($range) >= 2) {
				$search->addRange($name, array_shift($range), array_shift($range));
			} elseif (is_numeric($range)) {
				$search->addPriority($name, (int)$range);
			}
		}

		$this->view->set('results', $search);
		/** @psalm-suppress UndefinedMethod */
		$this->respondTo('html')->append('tasks/index');
		/** @psalm-suppress UndefinedMethod */
		$this->respondTo('json')->addCallback(static function ($data) {
			return array(
				'bank' => $data['bank']->bank,
				'tasks' => $data['results']->tasks('serialisable')
			);
		});
	}

	/**
	 * PUT /tasks/[{$id}:i]
	 * method used to submit a change to an existing task entry.
	 * @param int $id The id of the task to be edited
	 */
	public function update(int $id): void {
		if (($task = $this->di_repo->find(TaskModel::class, $id)) === null) {
			$this->notFound("task with the id '#{$id}'");

			return;
		}

		if ($task->idUser !== $this->user->id) {
			$this->notAllowed(
				"User with id '{$this->user->id}' is not allowed to change/delete other users' tasks"
			);

			return;
		}

		if ((bool)($this->request->request->get('done'))) {
			$task->donedate = new DateTime();
			if ($task->save()) {
				//pay out the reward points
				$bank = $this->user->bank;
				$bank->bank += $task->donereward;
				$bank->save();
			}
		}
		$this->view->set('errors', false);
		$this->respondTo('json');
	}
}
