<?php
/**
* This file is part of the todo tracking software
 * (c) Matthias Lantsch
 *
 * Model class for the TaskModel model
 *
 * @package holonet todo app
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\models;

use holonet\common as co;
use holonet\activerecord\ModelBase;

/**
 * TaskModel class to wrap around the "task" database table
 *
 * @author  matthias.lantsch
 * @package holonet\todo\models
 */
class TaskModel extends ModelBase {

	/**
	 * property containing an array of validation definitions
	 *
	 * @access public
	 * @var    array $validate Array with verification data
	 */
	public static $validate = array(
		"duedate" => array("presence"),
		"name" => array("presence", "length" => array("min" => 5, "max" => 40)),
		"sphinx_id" => array("presence")
	);

}
