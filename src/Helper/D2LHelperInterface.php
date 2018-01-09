<?php

namespace SmithAndAssociates\LaravelValence\Helper;

interface D2LHelperInterface
{
	/**
	 * Retrieve the enrolled users in the classlist for an org unit.
	 *
	 * @param $orgUnitId
	 *
	 * @return mixed
	 */
	public function getClassList($orgUnitId);

	/**
	 * Retrieve a list of ancestor-units for a provided org unit.
	 *
	 * @param $orgUnitId
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getAncestors($orgUnitId, $params = []);

	/**
	 * Retrieve a list of descendent-units for a provided org unit.
	 *
	 * @param $orgUnitId
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getDescendants($orgUnitId, $params = []);

	/**
	 * Add Query Parameters.
	 *
	 * @param $path
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function addQueryParameters($path, $params = []);
}