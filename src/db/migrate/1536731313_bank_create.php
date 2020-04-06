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
 * create the bank table.
 */
class BankCreateMigration extends Migration {
	/**
	 * migration into the down direction.
	 */
	public function down(): void {
		$this->schema->dropTable('bank');
	}

	/**
	 * migration into the up direction.
	 */
	public function up(): void {
		$this->schema->createTable('bank', static function (TableBuilder $t): void {
			$t->float('bank')->default(0);
			$t->addReference('user');
		});
	}
}
