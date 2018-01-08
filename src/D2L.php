<?php

namespace SmithAndAssociates\LaravelValence;

use Desire2Learn\Valence\D2LHostSpec;
use Desire2Learn\Valence\D2LUserContext;
use Desire2Learn\Valence\D2LAppContext;
use Desire2Learn\Valence\D2LConstants;

class D2L implements D2LInterface {

	protected $host;
	protected $id;
	protected $key;
	protected $a;
	protected $b;

	/**
	 * @var D2LUserContext $userContext
	 */
	protected $userContext;

	public function __construct($host, $id, $key, $a, $b) {
		$this->host = $host;
		$this->id = $id;
		$this->key = $key;
		$this->a = $a;
		$this->b = $b;
		$authContext = new D2LAppContext($this->key, $this->id);
		$hostSpec = new D2LHostSpec($this->host, '443', 'https');
		$this->userContext = $authContext->createUserContextFromHostSpec(
			$hostSpec,
			$this->a,
			$this->b
		);
	}

	public function generateUrl( $path, $code, $method ) {
		$code = strtolower($code);
		$url = '/d2l/api/'.$code.'/';
		if ($code === 'lp') {
			$url .= D2LConstants::URI_LP_VERSION;
		} else if ($code === 'le') {
			$url .= D2LConstants::URI_LE_VERSION;
		} else if ($code === 'bas') {
			$url .= D2LConstants::URI_BAS_VERSION;
		} else {
			throw new \Exception('D2L Code '. $code . 'not supported.');
		}
		return $this->userContext->createAuthenticatedUri($url.$path, $method);
	}

	public function call( $path, $method = 'GET',  $body = [] ) {
		if ($path) {
			$method = strtoupper($method);
			$params = array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => $method,
				CURLOPT_URL => $url,
				CURLOPT_SSL_VERIFYPEER => true,
			);
			if ($method === 'POST') {
				$data = json_encode($body);
				$params = array_merge($params, array(
					CURLOPT_POSTFIELDS => $data,
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
						'Content-Length: '.strlen($data)
					)
				));
			}
			$ch = curl_init();
			curl_setopt_array($ch, $params);
			$response = curl_exec($ch);
			return json_decode($response, true);
		}
		return null;
	}

	public function test()
	{
		return 'hello';
	}

}