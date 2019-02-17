<?php

namespace TaylorNetwork\Linkify\Tests;

use Orchestra\Testbench\TestCase;
use TaylorNetwork\Linkify\Linkify;

class LinkifyTest extends TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    public function getPackageProviders($app)
    {
        return ['TaylorNetwork\\Linkify\\LinkifyServiceProvider'];
    }

    public function testRegularCall()
    {
        $linkify = new Linkify();

        $this->assertEquals(
            $linkify->parse('Google Link: https://www.google.com!'),
            'Google Link: [google.com](https://www.google.com)!'
        );
    }

    public function testStaticCall()
    {
        $this->assertEquals(
            Linkify::instance()->parse('Google Link: https://www.google.com!'),
            'Google Link: [google.com](https://www.google.com)!'
        );
    }

    public function testConvertHTML()
    {
        $linkify = new Linkify();

        $linkify->setConfig('convertTo', Linkify::ConvertHTML);

        $this->assertEquals(
            $linkify->parse('Google Link: https://www.google.com!'),
            'Google Link: <a href="https://www.google.com">google.com</a>!'
        );
    }

    public function testConvertHTMLWithAttributes()
    {
        $linkify = new Linkify();

        $linkify->setConfig('convertTo', Linkify::ConvertHTML);
        $linkify->setConfig('linkAttributes', [
            'class'  => 'btn-link',
            'target' => '_blank',
        ]);

        $this->assertEquals(
            $linkify->parse('Google Link: https://www.google.com!'),
            'Google Link: <a class="btn-link" target="_blank" href="https://www.google.com">google.com</a>!'
        );
    }

    public function testMultipleMarkdown()
    {
        $linkify = new Linkify();

        $this->assertEquals(
            $linkify->parse('Google Link: https://www.google.com! And another: https://github.com/taylornetwork/linkify'),
            'Google Link: [google.com](https://www.google.com)! And another: [github.com](https://github.com/taylornetwork/linkify)'
        );
    }

    public function testCustomCallback()
    {
        $linkify = new Linkify();
        $linkify->setConfig('convertTo', Linkify::ConvertCustom);
        $linkify->setConfig('customCallback', function ($caption, $url) {
            return '{'.$caption.'}**'.$url.'**';
        });

        $this->assertEquals(
            $linkify->parse('Google Link: https://www.google.com!'),
            'Google Link: {google.com}**https://www.google.com**!'
        );
    }

    public function testTraitWithCustom()
    {
        $testClass = new TestingClass();

        $this->assertEquals(
            $testClass->linkify('Google Link: https://www.google.com!'),
            'Google Link: ^https://www.google.com^$google.com$!'
        );
    }
}
