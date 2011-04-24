<?php

/**
 * @todo совместить и встроить это класс вместо joosSpoof
 */
class PPI_Security {

	/**
	 * Create a new CSRF key and set it in the session
	 * @return string The Token
	 */
	static function createCSRF() {
		$token = md5(uniqid(mt_rand(), true));
		self::setCSRF($token);
		return $token;
	}

	/**
	 * Validate CSRF key with one in the session
	 * @param string $token
	 * @return boolean
	 */
	static function checkCSRF($token) {
		return self::getCSRF() === $token;
	}

	/**
	 * Set the CSRF in the session
	 * @param string $token
	 */
	static function setCSRF($token) {
		PPI_Helper::getSession()->set('PPI_Security::csrfToken', $token);
	}

	/**
	 * Get the CSRF token from the session
	 * @return string
	 */
	static function getCSRF() {
		return PPI_Helper::getSession()->get('PPI_Security::csrfToken');
	}

}