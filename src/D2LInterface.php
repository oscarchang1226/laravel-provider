<?php

namespace SmithAndAssociates\LaravelValence;

interface D2LInterface {

	/**
	 * Generate D2L Valence API url with code and return secured url
	 *
	 * @param String $method
	 * @param String $code
	 * @param String $path
	 *
	 * @return String
	 */
	public function generateUrl($path, $code, $method);

	/**
	 * Call D2L Valence API and return result.
	 *
	 * @param String $path
	 * @param String $method
	 * @param Array $body
	 *
	 * @return mixed
	 */
	public function call($path, $method, $body);
}