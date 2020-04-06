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
use holonet\holofw\auth\UserModelInterface;
use holonet\holofw\auth\StandardUserModelTrait;

/**
 * UserModel class to wrap around the "user" database table.
 */
class UserModel extends ModelBase implements UserModelInterface {
	use StandardUserModelTrait;

	/**
	 * @var array $hasMany Relationship mappings
	 */
	public static $hasMany = array('tasks');

	/**
	 * @var array $hasOne Array with hasOne relationship mappings
	 */
	public static $hasOne = array('bank' => array('forced' => false));

	/**
	 * @var array $validate Array with verification data for some of the columns
	 */
	public static $validate = array(
		'username' => array('presence')
	);

	/**
	 * {@inheritdoc}
	 */
	public static function supportedUserClaims(): array {
		return array(
			'username' => 'username'
		);
	}
}
