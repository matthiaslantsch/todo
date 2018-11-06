<?php
/**
 * This file is part of {partof}
 * (c) Matthias Lantsch
 *
 * procedural entry point file for web requests
 *
 * @package {app}
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

$rootdir = dirname(__DIR__);
require_once implode(DIRECTORY_SEPARATOR, array($rootdir, "vendor", "autoload.php"));

$app = new holonet\holofw\FWApplication($rootdir, "{app}");
$request = holonet\http\HttpRequest::createFromGlobals();
$app->handle($request)->send();
