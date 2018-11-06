<?php
/**
 * This file is part of the todo holonet task management software
 * (c) Matthias Lantsch
 *
 * file for common viewhelper functions
 */

/**
 * small helper function translating a priority integer to a warning colour
 *
 * @param  interger $priority The priority integer to translate
 * @return string the priority colour string for bootstrap
 */
function priorityColour(int $priority) {
	if($priority >= 5) {
		return "danger";
	} elseif($priority == 4) {
		return "warning";
	} elseif($priority <= 3 && $priority >= 2) {
		return "info";
	} else {
		return "success";
	}

}
