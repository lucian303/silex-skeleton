<?php

use App\Application;

class ApplicationTest extends PHPUnit_Framework_TestCase
{
	public function testAppVersion()
	{
		$app = new App\Application();
		$this->assertTrue('0.0.1-dev' == $app->getAppVersion());
	}
}
