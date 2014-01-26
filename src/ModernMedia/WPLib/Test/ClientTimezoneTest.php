<?php
namespace ModernMedia\WPLib\Test;

use ModernMedia\WPLib\ClientTimezone;

class ClientTimezoneTest extends \PHPUnit_Framework_TestCase  {
	public function testConstruct(){
		$tz = ClientTimezone::inst();
		//$this->assertInstanceOf('\\HalfNickel\\App\\App', $app);
		return $tz;
	}
}

