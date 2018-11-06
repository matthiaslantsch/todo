<?php
# encoding: UTF-8
# This file is auto-generated from the current state of the database. Instead
# of editing this file, please use the migrations feature of Active Record to
# incrementally modify your database, and then regenerate this schema definition.
#
# Note that this schema.php definition is the main source for your
# database schema. To recreate the database, do not run all migrations, use the
# db/schema::load task
#
# It's strongly recommended that you check this file into your version control system.

use holonet\activerecord\Schema;

##
## bank #
##
Schema::createTable("bank", function($table) {
	$table->integer("sphinx_id");
	$table->float("bank", 10, 0)->default(0);
	$table->version("1536731313");
});

##
## task #
##
Schema::createTable("task", function($table) {
	$table->string("name", 40);
	$table->timestamp("duedate");
	$table->integer("steps")->default(1);
	$table->integer("donesteps")->default(0);
	$table->timestamp("donedate")->nullable();
	$table->integer("stepreward")->default(0);
	$table->integer("donereward")->default(0);
	$table->integer("priority")->default(1);
	$table->integer("sphinx_id");
	$table->version("1536731314");
});

##
## reward #
##
Schema::createTable("reward", function($table) {
	$table->integer("idTask");
	$table->string("key", 10);
	$table->integer("amount");
	$table->version("1536731315");
});

##
## reward references #
##
Schema::changeTable("reward", function($table) {
	$table->addReference("task", "idTask", "idTask");
	$table->version("1536731315");
});