<?php

namespace SmithAndAssociates\LaravelValence;

use Desire2Learn\Valence\D2LHostSpec;
use Desire2Learn\Valence\D2LUserContext;
use Desire2Learn\Valence\D2LAppContext;
use Desire2Learn\Valence\D2LConstants;

class D2L implements D2LInterface
{
	/**
	 * @var D2LAppContext $authContext
	 */
	protected $authContext;

	/**
	 * @var D2LUserContext $userContext
	 */
	protected $userContext;

	public function __construct($host, $id, $key, $a, $b) {
		$this->authContext = new D2LAppContext($id, $key);
		$hostSpec = new D2LHostSpec($host, '443', 'https');
		$this->userContext = $this->authContext->createUserContextFromHostSpec(
			$hostSpec,
			$a,
			$b
		);
	}

    public function getAuthContext()
    {
        return $this->authContext;
    }


    public function generateUrl( $path, $code = null, $method = 'GET' ) {
		$url = '/d2l/api';
		if ($code) {
			$code = strtolower($code);
			$url .= '/'.$code.'/';
			if ($code === 'lp') {
				$url .= D2LConstants::URI_LP_VERSION;
			} else if ($code === 'le') {
				$url .= D2LConstants::URI_LE_VERSION;
			} else if ($code === 'bas') {
				$url .= D2LConstants::URI_BAS_VERSION;
			} else if ($code === 'lr') {
				$url .= D2LConstants::URI_LR_VERSION;
			}
		}
		return $this->userContext->createAuthenticatedUri($url.$path, $method);
	}

	public function callAPI( $path, $method = 'GET', $body = [] ) {
		if ($path) {
			$method = strtoupper($method);
			$params = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_SSL_VERIFYPEER => true,
			);
			if ($method === 'POST' || $method === 'PUT') {
				$data = json_encode($body);
				$params[CURLOPT_POSTFIELDS] = $data;
				$params[CURLOPT_HTTPHEADER] = array(
					'Content-Type: application/json',
					'Content-Length: '.strlen($data)
				);
			}
			$ch = curl_init($path);
			curl_setopt_array($ch, $params);
			$response = curl_exec($ch);
			$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			$contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
			$responseCode = $this->userContext->handleResult($response, $httpCode, $contentType);

			curl_close($ch);

			if ($responseCode === D2LUserContext::RESULT_OKAY) {
				return json_decode($response, true);
			}

			return [
				'error' => 'API call failed: '. $httpCode .' '. $response,
				'path' => $path
			];
		}
		return 'No Path Found';
	}


}