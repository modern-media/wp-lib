<?php
namespace ModernMedia\WPLib\SocialSharing\Data;
use ModernMedia\WPLib\Data\BaseData;

/**
 * Class TweetButtonParams
 * @package ModernMedia\WPLib\SocialSharing\Data
 *
 * url	URL of the page to share
 * via	Screen name of the user to attribute the Tweet to
 * text	Default Tweet text
 * related	Related accounts
 * count	Count box position
 * lang	The language for the Tweet Button
 * counturl	URL to which your shared URL resolves
 * hashtags	Comma separated hashtags appended to tweet text
 * size	The size of the rendered button
 * dnt	See this section for information
 */
class TweetButtonParams extends BaseData {

	/**
	 * @var string
	 */
	public $url = '';

	/**
	 * @var string
	 */
	public $text = '';

	/**
	 * @var string
	 */
	public $via = '';

	/**
	 * @var string
	 */
	public $related = '';

	/**
	 * @var string
	 */
	public $hashtags = '';


	/**
	 * @var string
	 */
	public $count = 'none';

	/**
	 * @var string
	 */
	public $size = 'medium';

	/**
	 * @var string
	 */
	public $counturl = '';

	/**
	 * @var string
	 */
	public $lang = '';

	/**
	 * @var string
	 */
	public $dnt = '';


} 