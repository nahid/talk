<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 31/10/2018
 * Time: 09:14
 */

namespace Nahid\Talk\Tests\Html;


use Embera\Embera;
use Illuminate\Contracts\Support\Htmlable;
use Nahid\Talk\Html\HtmlString;
use Nahid\Talk\Tests\TestCase;

class HtmlStringTest extends TestCase
{
    /**
     * @var HtmlString
     */
    private $instance;

    /**
     * @var string
     */
    private $message;

    public function setUp()
    {
        parent::setUp();
        $this->message = "A message send on chat";
        $driver = $this->getMockBuilder(Embera::class)
            ->getMock();
        $driver->method('autoEmbed')
            ->willReturn($this->message);
        $this->instance = new HtmlString($this->message, $driver);
    }

    public function tearDown()
    {
        $this->instance = null;
        parent::tearDown();
    }

    public function testInstanceExists()
    {
        $this->assertNotNull($this->instance);
    }

    public function testIsHtmlable()
    {
        $this->assertInstanceOf(Htmlable::class, $this->instance);
    }

    public function testHasEmberaDriver()
    {
        $this->assertInstanceOf(Embera::class, $this->instance->getDriver());
    }

    public function testToHtmlIsString()
    {
        $this->assertEquals($this->message, $this->instance->toHtml());
    }
}
