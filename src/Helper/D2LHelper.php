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
	 * @param D2L $d2l
	 */
	public function __construct( D2L $d2l ) {
		$this->d2l = $d2l;
	}

    public function addCourseOffering($params)
    {
        $path = $this->d2l->generateUrl('/courses/', 'lp', 'POST');
        return $this->d2l->callAPI($path, 'POST', $params);
    }

    public function addCourseTemplate($params)
    {
        $path = $this->d2l->generateUrl('/courseTemplates/', 'lp', 'POST');
        return $this->d2l->callAPI($path, 'POST', $params);
    }

    public function getUrlToAuthenticate($host, $port = 443)
    {
        return $this->d2l->getAuthContext()->createUrlForAuthentication($host, $port, 'https://udev.smithbuy.com/');
    }

    public function dismissUser( $userId, $orgUnit ) {
		$path = $this->d2l->generateUrl(
			'/enrollments/orgUnits/' . $orgUnit . '/users/' . $userId,
			'lp',
			'DELETE'
		);
		return $this->d2l->callAPI($path, 'DELETE');
	}

	public function getUserData( $userId ) {
		$path = $this->d2l->generateUrl('/users/' . $userId, 'lp');
		return $this->d2l->callAPI($path);
	}

	public function updateUserActivation( $userId, $isActive ) {
		$path = $this->d2l->generateUrl('/users/' . $userId . '/activation', 'lp', 'PUT');
		return $this->d2l->callAPI($path, 'PUT', ['IsActive' => $isActive]);
	}

	public function getUserActivation( $userId ) {
		$path = $this->d2l->generateUrl('/users/' . $userId . '/activation', 'lp');
		return $this->d2l->callAPI($path);
	}

	public function enrollUser( $data ) {
		$path = $this->d2l->generateUrl('/enrollments/', 'lp', 'POST');
		return $this->d2l->callAPI($path, 'POST', $data);
	}


	public function associateAward( $orgUnit, $data ) {
		$path = $this->d2l->generateUrl('/orgunits/' . $orgUnit . '/associations/', 'bas', 'POST');
		return $this->d2l->callAPI($path, 'POST', $data);
	}

	public function issueAnAward( $orgUnitId, $data ) {
		$path = $this->d2l->generateUrl('/orgunits/' . $orgUnitId . '/issued/', 'bas', 'POST');
		return $this->d2l->callAPI($path, 'POST', $data);
	}

	public function createDataExport( $data ) {
		$path = $this->d2l->generateUrl('/dataExport/create', 'lp', 'POST');
		return $this->d2l->callAPI($path, 'POST', $data);
	}

	public function getDataExportBdsList() {
		$path = $this->d2l->generateUrl('/dataExport/bds/list', 'lp');
		return $this->d2l->callAPI($path);
	}

	public function downloadDataExportBds( $pluginId ) {
		$path = $this->d2l->generateUrl('/dataExport/bds/download/' . $pluginId, 'lp');
		return $path;
	}

	public function getDataExportList() {
		$path = $this->d2l->generateUrl('/dataExport/list', 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getDataExportJobs() {
		$path = $this->d2l->generateUrl('/dataExport/jobs', 'lp');
		return $this->d2l->callAPI($path);
	}

	public function downloadDataExport( $jobId ) {
		$path = $this->d2l->generateUrl('/dataExport/download/' . $jobId, 'lp');
		return $path;
	}

	public function getRoles() {
		$path = $this->d2l->generateUrl('/roles/', 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getUsers( $params = [] ) {
		$path = $this->addQueryParameters('/users/', $params);
		$path = $this->d2l->generateUrl($path, 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getOrgStructure( $params = [] ) {
		$path = $this->addQueryParameters('/orgstructure/', $params);
		$path = $this->d2l->generateUrl($path, 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getOuTypes() {
		return $this->d2l->callAPI(
			$this->d2l->generateUrl('/outypes/', 'lp')
		);
	}

	public function getChildless( $params = [] ) {
		$path = $this->addQueryParameters('/orgstructure/childless/', $params);
		$path = $this->d2l->generateUrl($path, 'lp');
		return $this->d2l->callAPI($path);
	}

	public function getCourseTOC( $orgUnitId, $params = [] ) {
		$path = $this->addQueryParameters('/'.$orgUnitId.'/content/toc', $params);
		$path = $this->d2l->generateUrl($path, 'le');
		return $this->d2l->callAPI($path);
	}

	public function getOrgClassAwards( $orgUnit, $params = [] ) {
		$path = $this->addQueryParameters('/orgunits/'.$orgUnit.'/classlist/', $params);
		$path = $this->d2l->generateUrl($path, 'bas');
		return $this->d2l->callAPI($path);
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
			if ($value) {
				if ($path[strlen($path)-1] !== '&') {
					$path .= '?';
				}
				$path .= $key . '=' . urlencode($value) . '&';
			}
		}
		return $path;
	}

}