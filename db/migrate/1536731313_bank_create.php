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
 * create the bank table
 *
 * @author  matthias.lantsch
 * @package holonet\todo\db\migrate
 */
class BankCreateMigration implements Migration {

	/**
	 * migration into the up direction
	 */
	public static function up() {
		Schema::createTable('bank', function($t) {
			$t->integer("sphinx_id");
			$t->float("bank")->default(0);
			$t->version("1536731313");
		});
	}

	/**
	 * migration into the down direction
	 */
	public static function down() {
		Schema::dropTable("bank");
	}

}
