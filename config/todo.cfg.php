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
	"namespace" => "holonet",
	"author" => array(
		"name" => "Matthias Lantsch",
		"email" => "matthias.lantsch@bluewin.ch"
	),
	"license" => "http://www.wtfpl.net/ Do what the fuck you want Public License",
	"partOf" => "the todo tracking software"
);

if(filter_var($_ENV['USE_REMOTE_AUTH_SYSTEM'], FILTER_VALIDATE_BOOLEAN)) {
	$config["auth"] = array(
		"realm" => "sphinx_auth",
		"flow" => \holonet\sphinxauth\SphinxAuthFlow::class,
		"usermodel" => \holonet\todo\models\UserModel::class,
		"login_route" => "homepage",
		"sphinx" => array(
			"provider_url" => "%env(SPHINX_URL)%",
			"client_id" => "%env(SPHINX_CLIENT_ID)%",
			"client_secret" => "%env(SPHINX_CLIENT_SECRET)%",
			"realm" => "%env(SPHINX_REALM)%"
		)
	);
} elseif(filter_var($_ENV['DEV_MODE'], FILTER_VALIDATE_BOOLEAN)) {
	$config["auth"] = array(
		"flow" => \holonet\holofw\auth\flow\PromptAuthFlow::class,
		"handler" => \holonet\holofw\auth\handler\DevAuthHandler::class,
		"usermodel" => \holonet\todo\models\UserModel::class,
	);
} else {
	$config["auth"] = array(
		"flow" => \holonet\holofw\auth\flow\PromptAuthFlow::class,
		"handler" => \holonet\holofw\auth\handler\FlatfileAuthHandler::class,
		"usermodel" => \holonet\todo\models\UserModel::class,
	);
}

// set default session lifetime for this application to 1 hour
$config['session']['lifetime'] = 3600;
