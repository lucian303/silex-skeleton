<?php

namespace App;

class Application extends \Silex\Application
{
	protected $appVersion = '0.0.1-dev';

	public function getAppVersion()
	{
		return $this->appVersion;
	}

	public function setAppVersion($appVersion)
	{
		$this->appVersion = $appVersion;
	}
}
