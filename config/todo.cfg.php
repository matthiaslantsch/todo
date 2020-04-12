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

$config["timezone"] = "Europe/Zurich";

$config["database"] = array(
	"driver" => "%env(DB_DRIVER)%",
	"file" => "%env(DB_FILE)%",
	"host" => "%env(DB_HOST)%",
	"port" => "%env(DB_PORT)%",
	"socket" => "%env(DB_SOCKET)%",
	"username" => "%env(DB_USER)%",
	"password" => "%env(DB_PASSWORD)%",
);

$config["vendorInfo"] = array(
	"namespace" => "holonet"
);

$config["auth"] = array(
	"realm" => "sphinx_auth",
	"flow" => \holonet\sphinxauth\SphinxAuthFlow::class,
	"usermodel" => \holonet\todo\models\UserModel::class,
	"loginurl" => "",
	"sphinx" => array(
		"provider_url" => "%env(SPHINX_URL)%",
		"client_id" => "%env(SPHINX_CLIENT_ID)%",
		"client_secret" => "%env(SPHINX_CLIENT_SECRET)%",
		"realm" => "%env(SPHINX_REALM)%"
	)
);
