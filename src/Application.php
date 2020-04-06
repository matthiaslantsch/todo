<?php
/**
 * This file is part of the holonet todo tracking app
 * (c) Matthias Lantsch.
 *
 * @license http://www.wtfpl.net/ Do what the fuck you want Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo;

use holonet\holofw\FWApplication;

class Application extends FWApplication {
	public const APP_NAME = 'todo';

	public function __construct() {
		parent::__construct(dirname(__DIR__));
	}
}
