<?php

namespace SmithAndAssociates\LaravelValence\Helper;

interface D2LHelperInterface
{

    /**
     * Retrieve a specific grade value for a particular user assigned in an org unit.
     *
     * @param $orgUnitId
     * @param $gradeObjectId
     * @param $userId
     * @return mixed
     */
    public function getUserGradeValueInOrgUnit ($orgUnitId, $gradeObjectId, $userId);

    /**
     * Retrieve all the current grade objects for a particular org unit.
     *
     * @param $orgUnitId
     * @return mixed
     */
    public function getOrgUnitGradeObjects ($orgUnitId);

    /**
     * Retrieve each user’s grade value for a particular grade object.
     *
     * @param $orgUnitId
     * @param $gradeObjectId
     * @return mixed
     */
    public function getOrgUnitGradeValues ($orgUnitId, $gradeObjectId);

    /**
     * Get all users
     *
     * @param array $params
     * @return mixed
     */
    public function getAllUsers ($params = []);

    /**
     *
     *
     * @param $orgUnit
     * @param $ltiLink
     * @return mixed
     */
    public function buildQuicklinkWithLtiLink ($orgUnit, $ltiLink);

    /**
     * Register a new LTI link for an org unit.
     *
     * @param $orgUnit
     * @param $params
     * @return mixed
     */
    public function registerLtiLink ($orgUnit, $params);

    /**
     * Retrieve the information for a particular LTI link.
     *
     * @param $orgUnit
     * @param $ltiLinkId
     * @return mixed
     */
    public function getLtiLinkInfo($orgUnit, $ltiLinkId);

    /**
     * Update the information associated with a registered LTI link.
     *
     * @param $ltiLinkId
     * @param $linkData
     * @return mixed
     */
    public function updateLtiLink($ltiLinkId, $linkData);

    /**
     * Retrieve the information for all LTI links registered for an org unit.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function getLtiLink ($orgUnit);

    /**
     * Retrieve the information for all LTI tool providers registered for an org unit.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function getLtiToolProvider ($orgUnit);

    /**
     * Generate one dimension array of course table of contents
     *
     * @param $orgUnit
     * @param array $params
     * @return mixed
     */
    public function generateCourseTOCArray ($orgUnit, $params = []);
    /**
     * Retrieve a course offering.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function getCourseOffering ($orgUnit);

    /**
     * Update a current course offering.
     *
     * @param $orgUnit
     * @param $params
     * @return mixed
     */
    public function updateCourseOffering ($orgUnit, $params);

    /**
     * Return Course Offering Info Object
     *
     * @param $name
     * @param $code
     * @param $isActive
     * @param null $startDate
     * @param null $endDate
     * @return mixed
     */
    public function generateCourseOfferingInfo ($name, $code, $isActive, $startDate = null, $endDate = null);

    /**
     * Retrieve all descendants with given org unit.
     *
     * @param $orgUnit
     * @param array $params
     * @return mixed
     */
    public function getAllOrgUnitDescendants ($orgUnit, $params = []);

    /**
     * Retrieve all of given org unit children.
     *
     * @param $orgUnit
     * @param int $type
     * @return mixed
     */
    public function getAllOrgUnitChildren ($orgUnit, $type = 101);

    /**
     * Retrieve all of user's enrollments.
     *
     * @param $userId
     * @param array $params
     * @return mixed
     */
    public function getAllEnrollments ($userId, $params = []);

    /**
     * Retrieve all org structure with given params.
     *
     * @param array $params
     * @return mixed
     */
    public function getAllOrgStructure ($params = []);

    /**
     * Retrieve the course image for a course offering.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function getCourseImageUrl ($orgUnit);

    /**
     * Retrieve the user progress items in an org unit, for specific users or content topics.
     * Params: userId, objectId, pageSize=20
     *
     * @param $orgUnit
     * @param $params
     * @return mixed
     */
    public function getUserProgress ($orgUnit, $params);

    /**
     * Sort by Identifier then pop last item
     *
     * @param $pageResult
     * @return mixed
     */
    public function getLastPagedResultItem ($pageResult);

    /**
     * Retrieve rubrics for an object in an org unit.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function getRubrics($orgUnit);

    /**
     * Delete an award’s association with an org unit.
     *
     * @param $orgUnit
     * @param $associationId
     * @return mixed
     */
    public function deleteAssociation ($orgUnit, $associationId);

    /**
     * Retrieve an org unit’s associations.
     *
     * @param $orgUnit
     * @param array $params
     * @return mixed
     */
    public function getOrgUnitAssociations ($orgUnit, $params = []);

    /**
     * Get items of paged result
     *
     * @param $pagedResult
     * @return mixed
     */
    public function getPagedResultItems ($pagedResult);

    /**
     * Updates paged result with new page result
     *
     * @param $oldPage
     * @param $newPage
     * @return mixed
     */
    public function updatePagedResult ($oldPage, $newPage);

    /**
     * Check if there are more item in paged result
     *
     * @param $pagedResult
     * @return mixed
     */
    public function hasMoreItem ($pagedResult);

    /**
     * Get bookmark for paged result object
     *
     * @param $pagedResult
     * @return mixed
     */
    public function getBookmark ($pagedResult);

	/**
	 * Update a custom org unit’s properties
	 *
	 * @param $orgUnit
	 *
	 * @return mixed
	 */
	public function getOrgStructureProperties($orgUnit);

	/**
	 * Update a custom org unit’s properties
	 *
	 * @param $orgUnit
	 * @param $params
	 *
	 * @return mixed
	 */
	public function updateOrgStructure($orgUnit, $params);

	/**
	 * Retrieve a list of all enrollments for the provided user.
	 *
	 * @param $userId
	 * @param $params
	 *
	 * @return mixed
	 */
	public function getUserEnrollments ($userId, $params);

	/**
	 * Queue up a new course copy job request.
	 *
	 * @param $orgUnit
	 * @param $params
	 *
	 * @return mixed
	 */
	public function copyOrgUnitComponent ($orgUnit, $params);

    /**
     * Delete a course template
     *
     * @param $orgUnit
     * @return mixed
     */
    public function deleteCourseTemplate ($orgUnit);

    /**
     * Delete a course offering.
     *
     * @param $orgUnit
     * @return mixed
     */
    public function deleteCourseOffering ($orgUnit);

    /**
     * Retrieve a list of child-units for a provided org unit.
     *
     * @param $orgUnit
     * @param $type
     * @param null $bookmark
     * @return mixed
     */
    public function getOrgUnitChildren ($orgUnit, $type, $bookmark = null);

    /**
     * Create a new custom org unit.
     *
     * @param $params
     * @return mixed
     */
    public function addOrgStructure ($params);

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
     * Update data for a particular user.
     *
     * @param $userId
     * @param $updateUserData
     * @return mixed
     */
    public function updateUser( $userId, $updateUserData);

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
     * @param array $params
     * @param null $orgUnit
     * @return mixed
     */
	public function getUserAwards($userId, $params = [], $orgUnit = null);
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
