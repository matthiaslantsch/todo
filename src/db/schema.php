<?php
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.php definition is the main source for your
# database schema. To recreate the database, do not run all migrations, use the
# db/schema::load task
#
# It's strongly recommended that you check this file into your version control system.

use holonet\activerecord\Database;
use holonet\dbmigrate\builder\TableBuilder;

if(!isset($database) || !$database instanceof Database) {
	throw new LogicException("Cannot include database schema without supplying the database object");
}
$schema = $database->schema();

##
## reward #
##
$schema->createTable("reward", function(TableBuilder $table) {
	$table->integer("idTask");
	$table->string("key", 10);
	$table->integer("amount");
	$table->version("1536731315");
});

##
## user #
##
$schema->createTable("user", function(TableBuilder $table) {
	$table->string("username");
	$table->addColumn("externalid", "uuid")->unique();
	$table->version("1536731312");
});

##
## task #
##
$schema->createTable("task", function(TableBuilder $table) {
	$table->string("name", 40);
	$table->timestamp("duedate");
	$table->integer("steps")->default(1);
	$table->integer("donesteps")->default(0);
	$table->timestamp("donedate")->nullable();
	$table->integer("stepreward")->default(0);
	$table->integer("donereward")->default(0);
	$table->integer("priority")->default(1);
	$table->integer("idUser");
	$table->version("1536731314");
});

##
## bank #
##
$schema->createTable("bank", function(TableBuilder $table) {
	$table->float("bank", 10, 0)->default(0);
	$table->integer("idUser");
	$table->version("1536731313");
});

##
## task references #
##
$schema->changeTable("task", function(TableBuilder $table) {
	$table->addReference("user", "idUser", "idUser");
	$table->version("1536731314");
});

##
## bank references #
##
$schema->changeTable("bank", function(TableBuilder $table) {
	$table->addReference("user", "idUser", "idUser");
	$table->version("1536731313");
});