<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\models;

use holonet\activerecord\sets\ModelSet;
use holonet\activerecord\annotation\Table;
use holonet\holofw\auth\StandardUserModelTrait;
use holonet\activerecord\annotation\relation\HasOne;
use holonet\activerecord\annotation\relation\HasMany;
use holonet\activerecord\annotation\validate\Required;

/**
 * @Table("user")
 */
class UserModel extends \holonet\holofw\auth\UserModel {
	use StandardUserModelTrait;

	/**
	 * @HasOne("bank")
	 */
	protected BankModel $bank;

	/**
	 * @Required
	 */
	protected string $externalid;

	/**
	 * @var ModelSet|TaskModel[] $tasks
	 * @HasMany("tasks")
	 */
	protected ModelSet $tasks;

	/**
	 * @Required
	 */
	protected string $username;

	/**
	 * {@inheritDoc}
	 */
	public static function supportedUserClaims(): array {
		return array(
			'username' => 'username'
		);
	}
}
