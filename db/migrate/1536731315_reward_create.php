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
 * create the reward table
 *
 * @author  matthias.lantsch
 * @package holonet\todo\db\migrate
 */
class RewardCreateMigration implements Migration {

	/**
	 * migration into the up direction
	 */
	public static function up() {
		Schema::createTable('reward', function($t) {
			$t->addReference("task");
			$t->string("key")->size("10");
			$t->integer("amount");
			$t->version("1536731315");
		});
	}

	/**
	 * migration into the down direction
	 */
	public static function down() {
		Schema::dropTable("reward");
	}

}
