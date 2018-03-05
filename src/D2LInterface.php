<?php

namespace SmithAndAssociates\LaravelValence;

use Desire2Learn\Valence\D2LAppContext;

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
	public function generateUrl($path, $code, $method = 'GET');

	/**
	 * Call D2L Valence API and return result.
	 *
	 * @param String $path
	 * @param String $method
	 * @param Array $body
	 *
	 * @return mixed
	 */
	public function callAPI($path, $method = 'GET', $body = []);

    /**
     * Get D2L authntication contest
     *
     * @return D2LAppContext
     */
	public function getAuthContext();
}