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
 * BankModel class to wrap around the "bank" database table.
 */
class BankModel extends ModelBase {
	/**
	 * @var array $belongsTo Array with definitions for a belongsTo relationship
	 */
	public static $belongsTo = array('user');

	/**
	 * @var array $defaults Array with defaults for attributes
	 */
	protected static $defaults = array('bank' => 0);
}
