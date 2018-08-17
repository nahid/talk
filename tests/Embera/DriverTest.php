<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 31/10/2018
 * Time: 09:36
 */

namespace Nahid\Talk\Tests\Embera;


use Embera\Embera;
use Nahid\Talk\Embera\Adapter;
use Nahid\Talk\Tests\Mocks\EmberaMock;
use Nahid\Talk\Tests\TestCase;

class DriverTest extends TestCase
{
    /**
     * @var Embera
     */
    private $driver;

    /**
     * @var string
     */
    private $host = 'codepen.io';

    /**
     * @var string
     */
    private $url = 'https://codepen.io/api/oembed';

    /**
     * @var string
     */
    private $message = 'here is the codepen http://codepen.io/FWeinb/pen/wjzyH&format=json';

    /**
     * @var array
     */
    private $mockResponse = [
        "embera_using_fake" => 0,
        "success"           => true,
        "type"              => "rich",
        "version"           => "1.0",
        "provider_name"     => "CodePen",
        "provider_url"      => "https://codepen.io",
        "title"             => "Rotation sphare pure css",
        "author_name"       => "Fabrice Weinberg",
        "author_url"        => "https://codepen.io/FWeinb/",
        "height"            => "300",
        "width"             => "800",
        "thumbnail_width"   => "384",
        "thumbnail_height"  => "225",
        "thumbnail_url"     => "https://s3-us-west-2.amazonaws.com/i.cdpn.io/315.wjzyH.small.61a87aea-b5d6-408a-a6f1-d679e0c32a74.png",
        "html"              => "<iframe id=\"cp_embed_wjzyH\" src=\"https://codepen.io/FWeinb/embed/preview/wjzyH?height=300&amp;slug-hash=wjzyH&amp;default-tabs=css,result&amp;host=https://codepen.io\" title=\"Rotation sphare pure css\" scrolling=\"no\" frameborder=\"0\" height=\"300\" allowtransparency=\"true\" class=\"cp_embed_iframe\" style=\"width: 100%; overflow: hidden;\"></iframe>"
    ];


    /**
     * @var string
     */
    private $expected = "here is the codepen <iframe id=\"cp_embed_wjzyH\" src=\"https://codepen.io/FWeinb/embed/preview/wjzyH?height=300&amp;slug-hash=wjzyH&amp;default-tabs=css,result&amp;host=https://codepen.io\" title=\"Rotation sphare pure css\" scrolling=\"no\" frameborder=\"0\" height=\"300\" allowtransparency=\"true\" class=\"cp_embed_iframe\" style=\"width: 100%; overflow: hidden;\"></iframe>";

    public function setUp()
    {
        parent::setUp();
        \Config::set('talk.oembed.enabled', true);
        \Config::set('talk.oembed.url', $this->url);
        $this->driver = new EmberaMock([], $this->mockResponse);
        $this->driver->addProvider($this->host, Adapter::class);
    }

    public function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }
    
    public function testMessageIsConverted()
    {
        $this->assertEquals($this->expected, $this->driver->autoEmbed($this->message));
    }
}
