<?php

namespace SmithAndAssociates\LaravelValence\Helper;

interface D2LHelperInterface
{
    /**
     * Create a new course offering.
     *
     * @param $params
     * @return mixed
     */
    public function addCourseOffering ($params);

    /**
     * Create a new course template.
     *
     * @param $params
     * @return mixed
     */
    public function addCourseTemplate ($params);

    /**
     *
     * Generate URL to authenticate D2L account
     *
     * @param $host
     * @param int $port
     * @return mixed
     */
    public function getUrlToAuthenticate($host, $port = 443);

	/**
	 * Retrieve data for a particular user.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getUserData($userId);

	/**
	 * Update a particular user’s activation settings.
	 *
	 * @param $userId
	 * @param $isActive
	 *
	 * @return mixed
	 */
	public function updateUserActivation($userId, $isActive);

	/**
	 * Retrieve a particular user’s activation settings.
	 *
	 * @param $userId
	 *
	 * @return mixed
	 */
	public function getUserActivation($userId);

	/**
	 * Create a new enrollment for a user.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function enrollUser($data);

	/**
	 * Delete a user’s enrollment in a provided org unit.
	 *
	 * @param $userId
	 * @param $orgUnit
	 *
	 * @return mixed
	 */
	public function dismissUser($userId, $orgUnit);

	/**
	 * Associate an award with an org unit.
	 *
	 * @param $orgUnit
	 * @param $data
	 *
	 * @return mixed
	 */
	public function associateAward($orgUnit, $data);

	/**
	 * Issue an award.
	 *
	 * @param $orgUnitId
	 * @param $data
	 *
	 * @return mixed
	 */
	public function issueAnAward($orgUnitId, $data);

	/**
	 * Create an export job for the requested data set.
	 *
	 * @param $data
	 *
	 * @return mixed
	 */
	public function createDataExport($data);

	/**
	 * Retrieves a list of Brightspace Data Sets plugins that you have permission to see.
	 *
	 * @return mixed
	 */
	public function getDataExportBdsList();

	/**
	 * Retrieves a file stream for the requested Brightspace Data Sets plugin.
	 *
	 * @param $pluginId
	 *
	 * @return mixed
	 */
	public function downloadDataExportBds($pluginId);

	/**
	 * Lists all available data sets.
	 *
	 * @return mixed
	 */
	public function getDataExportList();

	/**
	 * Lists all available export jobs that you have previously submitted.
	 *
	 * @return mixed
	 */
	public function getDataExportJobs();

	/**
	 * Retrieves a ZIP file containing a CSV file with the data of the requested export job that you previously submitted.
	 *
	 * @param $jobId
	 *
	 * @return mixed
	 */
	public function downloadDataExport($jobId);

	/**
	 * Retrieve a list of all known user roles.
	 *
	 * @return mixed
	 */
	public function getRoles();

	/**
	 * Retrieve data for one or more users.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getUsers($params = []);

	/**
	 * Retrieve properties for all org units.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOrgStructure($params = []);

	/**
	 * Retrieve all the known and visible org unit types.
	 *
	 * @return mixed
	 */
	public function getOuTypes();

	/**
	 * Retrieve all org units that have no children.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getChildless($params = []);

	/**
	 * Retrieve the table of course content for an org unit.
	 *
	 * @param $orgUnitId
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getCourseTOC($orgUnitId, $params = []);

	/**
	 * Retrieve the users in the classlist who are able to earn awards along with their first ten awards.
	 *
	 * @param $orgUnit
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getOrgClassAwards($orgUnit, $params = []);

	/**
	 * Retrieve the results for a query-based search across one or more repositories.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function searchObjects($params = []);

	/**
	 * Retrieve all repositories with the Search trust permission.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getAllRepositories($params = []);

	/**
	 * Retrieve all supported versions for all product components.
	 *
	 * @param $productCode
	 *
	 * @return mixed
	 */
	public function getVersions($productCode);

	/**
	 * Retrieve the awards issued to a user.
	 *
	 * @param $userId
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getUserAwards($userId, $params = []);
	/**
	 * Retrieve awards available across the organization.
	 *
	 * @param array $params
	 *
	 * @return mixed
	 */
	public function getAwards($params = []);

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