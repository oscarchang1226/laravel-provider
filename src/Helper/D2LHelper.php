<?php

namespace SmithAndAssociates\LaravelValence\Helper;

use SmithAndAssociates\LaravelValence\D2L;

class D2LHelper implements D2LHelperInterface
{
	/**
	 * @var D2L $d2l
	 */
	protected $d2l;

	/**
	 * D2LHelper constructor.
	 *
	 * @param \SmithAndAssociates\LaravelValence\D2L $d2l
	 */
	public function __construct( \SmithAndAssociates\LaravelValence\D2L $d2l ) {
		$this->d2l = $d2l;
	}

	public function searchObjects( $params = [] ) {
		$path = $this->addQueryParameters('/objects/search/', $params);
		$path = $this->d2l->generateUrl($path, 'lr');
		return $this->d2l->callAPI($path);
	}

	public function getAllRepositories( $params = [] ) {
		$path = $this->addQueryParameters('/repositories/all/', $params);
		$path = $this->d2l->generateUrl($path, 'lr');
		return $this->d2l->callAPI($path);
	}

	public function getVersions( $productCode ) {
		$path = $productCode ? '/'.$productCode.'/versions/' : '/versions/';
		$path = $this->d2l->generateUrl($path);
		return $this->d2l->callAPI($path);
	}


	public function getUserAwards( $userId, $params = [] ) {
		$path = $this->addQueryParameters('/issued/users/'.$userId.'/', $params);
		$path = $this->d2l->generateUrl($path, 'bas');
		return $this->d2l->callAPI($path);
	}


	public function getAwards( $params = [] ) {
		$path = $this->addQueryParameters('/awards/', $params);
		$path = $this->d2l->generateUrl($path, 'bas');
		return $this->d2l->callAPI($path);
	}


	public function getClassList( $orgUnitId ) {
		$path = $this->d2l->generateUrl('/'.$orgUnitId.'/classlist/', 'le');
		return $this->d2l->callAPI($path);
	}

	public function getAncestors( $orgUnitId, $params = [] ) {
		$path = $this->addQueryParameters(
			'/orgstructure/'.$orgUnitId.'/ancestors/',
			$params
		);
		$path = $this->d2l->generateUrl($path, 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getDescendants( $orgUnitId, $params = [] ) {
		$path = $this->addQueryParameters(
			'/orgstructure/'.$orgUnitId.'/descendants/paged/',
			$params
		);
		$path = $this->d2l->generateUrl($path, 'lp');
		return $this->d2l->callAPI($path);	}


	public function addQueryParameters( $path, $params = [] ) {
		foreach ($params as $key => $value) {
			if ($path[strlen($path)-1] !== '&') {
				$path .= '?';
			}
			$path .= $key . '=' . urlencode($value) . '&';
		}
		return $path;
	}

	public function test() {
		return $this->getDescendants(6606, ['ouTypeId' => 105]);
	}


}