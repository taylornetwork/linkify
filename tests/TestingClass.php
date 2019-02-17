<?php

namespace TaylorNetwork\Linkify\Tests;

use TaylorNetwork\Linkify\Linkify;
use TaylorNetwork\Linkify\MakesLinks;

class TestingClass
{
    use MakesLinks;

    public function linkifyCustomParse(string $caption, string $url)
    {
        return '^'.$url.'^$'.$caption.'$';
    }

    public function linkifyConfig(&$linkify)
    {
        $linkify->setConfig('convertTo', Linkify::ConvertCustom);
    }
}
