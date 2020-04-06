<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\models;

use holonet\activerecord\ModelBase;

/**
 * TaskModel class to wrap around the "task" database table.
 */
class TaskModel extends ModelBase {
	/**
	 * @var array $belongsTo Array with definitions for a belongsTo relationship
	 */
	public static $belongsTo = array('user');

	/**
	 * @var array $validate Array with verification data
	 */
	public static $validate = array(
		'duedate' => array('presence'),
		'name' => array('presence', 'length' => array('min' => 5, 'max' => 40))
	);
}
