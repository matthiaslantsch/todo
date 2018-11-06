<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch
 *
 * config file for project specific config options
 * the project specific config will override these values if set there
 *
 * @package holonet todo app
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

 /**
  * Database conntection configuration
  */
 $config["db"] = array(
 	/**
 	 * the pdo driver to be used for the database (need to have the driver+activerecord crud helpers installed)
 	 */
 	"driver" => "sqlite",
 	/**
 	 * The file of the sqlite database
 	 */
 	"file" => "%app.vardir%todo.db"
 );
