<?php 

namespace TaylorNetwork\Linkify\Tests;

use TaylorNetwork\Linkify\MakesLinks;
use TaylorNetwork\Linkify\Linkify;

class TestingClass
{
	use MakesLinks;

	public function linkifyCustomParse(string $caption, string $url)
	{
		return '^' . $url . '^$' . $caption . '$';
	}

	public function linkifyConfig(&$linkify)
	{
		$linkify->setConfig('convertTo', Linkify::ConvertCustom);
	}
}