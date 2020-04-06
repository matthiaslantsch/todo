<?php
/**
 * This file is part of the holonet todo tracking app
 * (c) Matthias Lantsch
 *
 * @package holonet todo app
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

use holonet\holofw\route\FWRouter;
use holonet\todo\controllers\TasksController;

/**
 * @var FWRouter $router
 */
$router = $this->router;

$router->index(array(
	"controller" => TasksController::class,
	"method" => "homepage"
));

$router->post(array(
	"url" => "tasks/index",
	"controller" => TasksController::class,
	"method" => "index"
));

$router->post(array(
	"url" => "tasks",
	"controller" => TasksController::class,
	"method" => "create"
));

$router->put(array(
	"url" => "tasks/[idTask:i]",
	"controller" => TasksController::class,
	"method" => "update"
));

$router->delete(array(
	"url" => "tasks/[idTask:i]",
	"controller" => TasksController::class,
	"method" => "delete"
));

$router->post(array(
	"url" => "bank",
	"controller" => TasksController::class,
	"method" => "bankChange"
));
