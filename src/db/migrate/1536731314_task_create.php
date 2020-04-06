<?php
/**
 * This file is part of the holonet todo software
 * (c) Matthias Lantsch.
 *
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\db\migrate;

use holonet\dbmigrate\Migration;
use holonet\dbmigrate\builder\TableBuilder;

/**
 * create the task table.
 */
class TaskCreateMigration extends Migration {
	/**
	 * migration into the down direction.
	 */
	public function down(): void {
		$this->schema->dropTable('task');
	}

	/**
	 * migration into the up direction.
	 */
	public function up(): void {
		$this->schema->createTable('task', static function (TableBuilder $t): void {
			$t->string('name')->size('40');
			$t->timestamp('duedate');
			$t->integer('steps')->default(1);
			$t->integer('donesteps')->default(0);
			$t->timestamp('donedate')->nullable();
			$t->integer('stepreward')->default(0);
			$t->integer('donereward')->default(0);
			$t->integer('priority')->default(1);
			$t->addReference('user');
		});
	}
}
