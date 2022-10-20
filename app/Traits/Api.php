<?php

namespace App\Traits;


trait Api
{

	private $api_url;

	public function __contruct()
	{
		$this->api_url = $this->getApiUrl();
	}

	private function getApiUrl()
	{
		return config('api.url');
	}
}
