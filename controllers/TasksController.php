<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch
 *
 * Class file for the TasksController
 *
 * @package holonet todo app
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\controllers;

use DateTime;
use RuntimeException;
use holonet\holofw\FWController;
use holonet\todo\models\TaskModel;
use holonet\todo\models\BankModel;
use holonet\todo\helpers\TaskList;

/**
 * TasksController exposing the task REST resource to the application user
 *
 * @author  matthias.lantsch
 * @package holonet\todo\controllers
 */
class TasksController extends FWController {
	/**
	 * ANY / (root homepage)
	 *
	 * @access public
	 * @return the yield from the controller method
	 */
	public function homepage() {
		$this->authenticateUser();
		yield "title" => "Todos for {$this->session->user->name}";
		$date = new DateTime();
		//check if it's weekend
		if($date->format("N") >= 6) {
			//only show the weekend todos (and important / critical)
			yield "showconfig" => array(
				"critical" => 5,
				"important" => 4,
				"weekend" => array(
					"from" => date("saturday", "Y-m-d H:i:s"),
					//go to end of sunday => monday midnight
					"to" => date("monday", "Y-m-d H:i:s"),
				)
			);
		} else {
			//default display style is a week showing every day except weekend in one
			//also show important / critical
			$ranges = array("critical" => 5, "important" => 4);
			$day = new DateTime("monday this week");
			while(($wday = $day->format("l")) !== "Saturday") {
				$ranges[$wday]["from"] = $day->format("Y-m-d H:i:s");
				$day->modify("+1 day");
				$ranges[$wday]["to"] = $day->format("Y-m-d H:i:s");
			}

			$ranges["weekend"]["from"] = $day->format("Y-m-d H:i:s");
			$day->modify("+2 days");
			$ranges["weekend"]["to"] = $day->format("Y-m-d H:i:s");
			//make it a sequential array to keep the order
			yield "showconfig" => $ranges;
		}
	}

	/**
	 * POST /tasks/index
	 * Display an index listing via json / html
	 *
	 * @access public
	 * @return the yield from the controller method
	 */
	public function index() {
		$this->authenticateUser();
		yield "bank" => BankModel::getOrCreateByUserId($this->session->user->db_id);
		$results = array();
		if(!$this->request->request->has("query")) {
			throw new RuntimeException("No search query definition was submitted", 100);
		}
		$queryOptions = array();
		$query = $this->request->request->get("query");
		$search = new TaskList($this->session->user->db_id);
		foreach ($query as $name => $range) {
			if(is_array($range) && count($range) >= 2) {
				$search->addRange($name, array_shift($range), array_shift($range));
			} elseif(is_numeric($range)) {
				$search->addPriority($name, intval($range));
			}
		}

		yield "results" => $search;
		$this->respondTo("html")->append("tasks/index");
		$this->respondTo("json")->addCallback(function($data) {
			return array(
				"bank" => $data["bank"]->bank,
				"tasks" => $data["results"]->tasks("serialisable")

			);
		});
	}

	/**
	 * POST /tasks
	 * method used to create a new task
	 *
	 * @access public
	 * @return the yield from the controller method
	 */
	public function create() {
		$this->authenticateUser();
		$rawdata = $this->request->request->getAll(array(
			"datetime", "priority", "description"
		));

		if($rawdata["description"] !== "") {
			preg_match("/^(.+?) ?(?:\[(\d+)\]|)$/", strip_tags($rawdata["description"]), $matches);
			$rawdata["description"] = $matches[1];
		}

		$data = array(
			"name" => $rawdata["description"],
			"donereward" => (isset($matches[2]) ? intval($matches[2]) : 0),
			"priority" => intval($rawdata["priority"]),
			"duedate" => strtotime($rawdata["datetime"]),
			"sphinx_id" => $this->session->user->db_id
		);

		$task = new TaskModel($data);

		if(($validResult = $task->valid()) === true) {
			if(!$task->save()) {
				throw new RuntimeException("Could not save new task valid?:".var_export($task->valid(), true));
			}

			yield "errors" => false;
		} else {
			yield "errors" => $validResult->getAll();
		}

		$this->respondTo("json");
	}

	/**
	 * PUT /tasks/[{$id}:i]
	 * method used to submit a change to an existing task entry
	 *
	 * @access public
	 * @param  integer $id The id of the task to be edited
	 * @return the yield from the controller method
	 */
	public function update(int $id) {
		$this->authenticateUser();
		if(($task = TaskModel::find($id)) === null) {
			$this->notFound("task with the id '#{$id}'");
		}

		if($task->sphinx_id != $this->session->user->db_id) {
			$this->notAllowed(
				"User with id '{$this->session->user->db_id}' is not allowed to change/delete other users' tasks"
			);
		}

		if(boolval($this->request->request->get("done"))) {
			$task->donedate = new DateTime();
			if($task->save()) {
				//pay out the reward points
				$bank = BankModel::getOrCreateByUserId($this->session->user->db_id);
				$bank->bank += $task->donereward;
				$bank->save();
			}
		}
		yield "errors" => false;
		$this->respondTo("json");
	}

	/**
	 * DELETE /tasks/[{$id}:i]
	 * method used to delete a task entry
	 *
	 * @access public
	 * @param  integer $id The id of the task to be deleted
	 * @return the yield from the controller method
	 */
	public function delete(int $id) {
		$this->authenticateUser();
		if(($task = TaskModel::find($id)) === null) {
			$this->notFound("task with the id '#{$id}'");
		}

		if($task->sphinx_id != $this->session->user->db_id) {
			$this->notAllowed(
				"User with id '{$this->session->user->db_id}' is not allowed to change/delete other users' tasks"
			);
		}
		$task->delete();
		yield "errors" => false;
		$this->respondTo("json");
	}

	/**
	 * POST /bank
	 * method used to update the reward bank of a user
	 *
	 * @access public
	 * @return the yield from the controller method
	 */
	public function bankChange() {
		$this->authenticateUser();
		$bank = BankModel::getOrCreateByUserId($this->session->user->db_id);
		$bank->bank += intval($this->request->request->get("change"));
		$bank->save();
		yield "errors" => false;
		$this->respondTo("json");
	}

}
