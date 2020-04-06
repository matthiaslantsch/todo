<?php
/**
 * This file is part of the todo tracking software
 * (c) Matthias Lantsch.
 *
 * @license http://opensource.org/licenses/gpl-license.php  GNU Public License
 * @author  Matthias Lantsch <matthias.lantsch@bluewin.ch>
 */

namespace holonet\todo\views\helpers;

class ViewUtils extends \holonet\holofw\viewhelpers\ViewUtils {
	/**
	 * small helper function translating a priority integer to a warning colour.
	 * @param int $priority The priority integer to translate
	 * @return string the priority colour string for bootstrap
	 */
	public static function priorityColour(int $priority): string {
		if ($priority >= 5) {
			return 'danger';
		}
		if ($priority === 4) {
			return 'warning';
		}
		if ($priority <= 3 && $priority >= 2) {
			return 'info';
		}

		return 'success';
	}
}
