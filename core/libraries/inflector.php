<?php

class joosInflector {

	public static function camelize($string) {
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $string)));
	}

	public static function underscore($string) {
		return strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $string));
	}

	public static function humanize($string) {
		return ucfirst(str_replace('_', ' ', $string));
	}

}
