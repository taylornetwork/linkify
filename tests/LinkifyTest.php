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
            'Google Link: [google.com](https://www.google.com)!',
            $linkify->parse('Google Link: https://www.google.com!')
        );
    }

    public function testStaticCall()
    {
        $this->assertEquals(
            'Google Link: [google.com](https://www.google.com)!',
            Linkify::instance()->parse('Google Link: https://www.google.com!')
        );
    }

    public function testConvertHTML()
    {
        $linkify = new Linkify();

        $linkify->setConfig('convertTo', Linkify::ConvertHTML);

        $this->assertEquals(
            'Google Link: <a href="https://www.google.com">google.com</a>!',
            $linkify->parse('Google Link: https://www.google.com!')
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
            'Google Link: <a class="btn-link" target="_blank" href="https://www.google.com">google.com</a>!',
            $linkify->parse('Google Link: https://www.google.com!')
        );
    }

    public function testMultipleMarkdown()
    {
        $linkify = new Linkify();

        $this->assertEquals(
            'Google Link: [google.com](https://www.google.com)! And another: [github.com](https://github.com/taylornetwork/linkify)',
            $linkify->parse('Google Link: https://www.google.com! And another: https://github.com/taylornetwork/linkify')
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
            'Google Link: {google.com}**https://www.google.com**!',
            $linkify->parse('Google Link: https://www.google.com!')
        );
    }

    public function testTraitWithCustom()
    {
        $testClass = new TestingClass();

        $this->assertEquals(
            'Google Link: ^https://www.google.com^$google.com$!',
            $testClass->linkify('Google Link: https://www.google.com!')
        );
    }

    public function testHasExisting()
    {
        $linkify = new Linkify();
        $this->assertEquals(
            'Google Link: [google.com](https://www.google.com)!',
            $linkify->parse('Google Link: [google.com](https://www.google.com)!')
        );
    }

    public function testHasExistingOtherFormat()
    {
        $linkify = new Linkify();
        $linkify->setConfig('convertTo', Linkify::ConvertMarkdown);
        $this->assertEquals(
            'Google Link: <a href="https://www.google.com">google.com</a>!',
            $linkify->parse('Google Link: <a href="https://www.google.com">google.com</a>!')
        );
    }
}
