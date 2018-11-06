<?php
/**
 * This file is part of the holonet todo software
 * (c) Matthias Lantsch
 *
 * class file for a migration
 *
 * @package holonet todo software
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\db\migrate;

use holonet\activerecord\Migration;
use holonet\activerecord\Schema;

/**
 * create the task table
 *
 * @author  matthias.lantsch
 * @package holonet\todo\db\migrate
 */
class TaskCreateMigration implements Migration {

	/**
	 * migration into the up direction
	 */
	public static function up() {
		Schema::createTable('task', function($t) {
			$t->string("name")->size('40');
			$t->timestamp("duedate");
			$t->integer("steps")->default(1);
			$t->integer("donesteps")->default(0);
			$t->timestamp("donedate")->nullable();
			$t->integer("stepreward")->default(0);
			$t->integer("donereward")->default(0);
			$t->integer("priority")->default(1);
			$t->integer("sphinx_id");
			$t->version("1536731314");
		});
	}

	/**
	 * migration into the down direction
	 */
	public static function down() {
		Schema::dropTable("task");
	}

}
