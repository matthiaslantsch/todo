<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\helpers;

use DateTime;
use ArrayIterator;
use RuntimeException;
use IteratorAggregate;
use holonet\todo\models\TaskModel;
use holonet\activerecord\ModelRepository;

/**
 * TaskList is a class that can be used to compile a search
 * for tasks that match a certain criteria.
 */
class TaskList implements IteratorAggregate {
	/**
	 * @var ModelRepository $repo Given repository object
	 */
	public $repo;

	/**
	 * @var array $conditions Array with conditions of what to select
	 */
	private $conditions = array();

	/**
	 * @var array $queryOptions Array with options for the query, compiled from the search options
	 */
	private $queryOptions = array();

	/**
	 * @var array|null $tasks The tasks that were found by the search
	 */
	private $tasks;

	/**
	 * @var int $user_id The id of the user
	 */
	private $user_id;

	/**
	 * @param ModelRepository $repo Reference to the model repository
	 * @param int $user_id The id of the user the search is for
	 */
	public function __construct(ModelRepository $repo, int $user_id) {
		$this->repo = $repo;
		$this->user_id = $user_id;
	}

	/**
	 * Directly add a condition concerning priority value.
	 * @param string $name The name to sort the results under
	 * @param int $prio The priority value to search for
	 */
	public function addPriority(string $name, int $prio): void {
		$this->conditions[$name] = $prio;
		$this->queryOptions['priority'][] = $prio;
		//delete the task cache
		$this->tasks = null;
	}

	/**
	 * Directly add a range using 2 date values
	 * date values from be either int (timestamp) or string (strtotime).
	 * @param string $name The name to sort the results under
	 * @param mixed $from The start time value of the range
	 * @param mixed $to The end time value of the range
	 */
	public function addRange(string $name, $from, $to): void {
		$from = $this->ensureDateString($from);
		$to = $this->ensureDateString($to);

		$this->conditions[$name] = array('from' => $from, 'to' => $to);
		$this->queryOptions['duedate[<>]'][] = array($from, $to);
		//delete the task cache
		$this->tasks = null;
	}

	/**
	 * Get the aggregate iterator
	 * IteratorAggregate interface required method.
	 * @return ArrayIterator to iterate over our internal data
	 */
	public function getIterator(): ArrayIterator {
		return new ArrayIterator($this->tasks());
	}

	/**
	 * small helper function ensuring the value is a "Y-m-d H:i:s" formatted time string.
	 * @param mixed $value The time value to convert
	 * @return string with the time string in the "Y-m-d H:i:s" format
	 */
	private function ensureDateString($value): string {
		if ($value instanceof DateTime) {
			return $value->format('Y-m-d H:i:s');
		}
		if (is_string($value)) {
			return (new DateTime($value))->format('Y-m-d H:i:s');
		}

		return (new DateTime("@{$value}"))->format('Y-m-d H:i:s');
	}

	/**
	 * method used to return the saved tasks in a certain sorting manner
	 * standard is "list", possibilities include:
	 * - list => multilevel list with name per condition
	 * - flat => all tasks in one array
	 * - serialisable => multilevel list with a toplevel array so we don't loose the order.
	 * @param string $manner The manner to sort the tasks in
	 * @return array with the tasks sorted according to $manner
	 */
	private function tasks(string $manner = 'list'): array {
		if ($this->tasks === null) {
			//include overdue tasks
			$this->queryOptions['AND'] = array('duedate[<]' => date('Y-m-d H:i:s'), 'donedate' => null);
			$this->tasks = $this->repo->select(TaskModel::class, array(
				'idUser' => $this->user_id,
				'OR' => $this->queryOptions
			));
		}

		if ($manner === 'flat') {
			return $this->tasks;
		}
		if ($manner === 'serialisable' || $manner === 'list') {
			//create a multilevel list with sublists
			//important that we keep the order in which the definitons were added
			//must be this way or else we loose the order when translating to e.g. JSON
			$ret = array();

			//especially include overdue tasks
			$sublist = array();
			foreach ($this->tasks as $task) {
				//check if it's overdue
				if ($task->duedate->format('db') < date('Y-m-d H:i:s')) {
					$sublist[] = $task;
				}
			}
			if ($manner === 'serialisable') {
				$ret[] = array('name' => 'overdue', 'tasks' => $sublist);
			} else {
				$ret['overdue'] = $sublist;
			}

			foreach ($this->conditions as $name => $cond) {
				$sublist = array();
				foreach ($this->tasks as $task) {
					if (is_int($cond)) {
						if ($task->priority === $cond) {
							$sublist[] = $task;
						}
					} elseif (is_array($cond) && count($cond) === 2) {
						if ($task->duedate->format('db') > $cond['from'] && $task->duedate->format('db') < $cond['to']) {
							$sublist[] = $task;
						}
					}
				}
				if ($manner === 'serialisable') {
					$ret[] = array('name' => $name, 'tasks' => $sublist);
				} else {
					$ret[$name] = $sublist;
				}
			}
		} else {
			throw new RuntimeException("Unknown manner to return tasks sorted as '{$manner}'");
		}

		return $ret;
	}
}
