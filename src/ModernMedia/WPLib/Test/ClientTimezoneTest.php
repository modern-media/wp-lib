<?php
namespace ModernMedia\WPLib\Test;

use Carbon\Carbon;
use ModernMedia\WPLib\ClientTimezone;

class ClientTimezoneTest extends \PHPUnit_Framework_TestCase  {
	public function testConstruct(){
		$tz = ClientTimezone::inst();
		$this->assertInstanceOf('\\ModernMedia\\WPLib\\ClientTimezone', $tz);
		return $tz;
	}

	/**
	 * @depends testConstruct
	 * @param ClientTimezone $tz
	 */
	public function testSetOffsetNewYork($tz){
		$df = 'Y-m-d H:i:s';

		$test_date_strings = array(
			'2014-01-26 10:03:36',
			'2014-05-26 23:03:36',
			'2012-02-29 23:03:36',
			'2015-12-31 23:59:59',
		);

		$timezone_identifiers = \DateTimeZone::listIdentifiers();

		foreach($test_date_strings as $test_date_string){
			foreach($timezone_identifiers as $tz_id){
				$utc = new Carbon($test_date_string, 'UTC');
				$local = $utc->copy();
				$local->setTimezone($tz_id);
				$offset = $local->getOffset()/60;
				$tz->set_offset($offset);
				$new_local = $tz->utc_to_local($utc);
				$this->assertEquals($local->format($df), $new_local->format($df));
			}
		}



	}
}

