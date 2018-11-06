<?php
/**
 * This file is part of the holonet todo tracking app
 * (c) Matthias Lantsch
 *
 * php route definition file
 *
 * @package holonet todo app
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

use holonet\holofw\FWRouter;

FWRouter::index(array(
	"controller" => "tasks",
	"method" => "homepage"
));

FWRouter::post(array(
	"url" => "tasks/index",
	"controller" => "tasks",
	"method" => "index"
));

FWRouter::post(array(
	"url" => "tasks",
	"controller" => "tasks",
	"method" => "create"
));

FWRouter::put(array(
	"url" => "tasks/[idTask:i]",
	"controller" => "tasks",
	"method" => "update"
));

FWRouter::delete(array(
	"url" => "tasks/[idTask:i]",
	"controller" => "tasks",
	"method" => "delete"
));

FWRouter::post(array(
	"url" => "bank",
	"controller" => "tasks",
	"method" => "bankChange"
));
