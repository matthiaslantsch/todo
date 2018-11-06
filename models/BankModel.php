<?php
/**
* This file is part of the todo tracking software
 * (c) Matthias Lantsch
 *
 * Model class for the BankModel model
 *
 * @package holonet todo app
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\models;

use holonet\common as co;
use holonet\activerecord\ModelBase;

/**
 * BankModel class to wrap around the "bank" database table
 *
 * @author  matthias.lantsch
 * @package holonet\todo\models
 */
class BankModel extends ModelBase {

	/**
	 * property containing an array of validation definitions
	 *
	 * @access public
	 * @var    array $validate Array with verification data
	 */
	public static $validate = array(
		"sphinx_id" => array("presence")
	);

	/**
	 * special shorthand getter funtion selecting by sphinx user id
	 * creates a new entry if it can't be found
	 *
	 * @access public
	 * @param  int $sphinx_id The id of the user to select the bank for
	 * @return instance of this class
	 */
	public static function getOrCreateByUserId(int $sphinx_id) {
		if(($ret = static::get(array("sphinx_id" => $sphinx_id))) === null) {
			$ret = static::create(array("sphinx_id" => $sphinx_id), true);
		}
		return $ret;
	}

}
