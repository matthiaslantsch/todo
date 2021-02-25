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
use holonet\activerecord\ChangeAwareDatetime;
use holonet\activerecord\annotation\validate\Length;
use holonet\activerecord\annotation\validate\Required;
use holonet\activerecord\annotation\relation\BelongsTo;

/**
 * @Table("task")
 */
class TaskModel extends ModelBase {
	protected ?ChangeAwareDatetime $donedate;

	protected int $donereward = 0;

	protected int $donesteps = 0;

	/**
	 * @Required
	 */
	protected ChangeAwareDatetime $duedate;

	/**
	 * @Required
	 * @Length(max=100)
	 */
	protected string $name;

	protected int $priority = 1;

	protected int $stepreward = 0;

	/**
	 * @Required
	 */
	protected int $steps = 0;

	/**
	 * @BelongsTo("user")
	 */
	protected UserModel $user;
}
