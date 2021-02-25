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
use holonet\activerecord\annotation\Table;
use holonet\activerecord\annotation\relation\BelongsTo;

/**
 * @Table("bank")
 */
class BankModel extends ModelBase {
	protected float $bank = 0;

	/**
	 * @BelongsTo("user")
	 */
	protected UserModel $user;
}
