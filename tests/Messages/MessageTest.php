<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 29/10/2018
 * Time: 09:13
 */
namespace Nahid\Talk\Tests\Messages;


use Nahid\Talk\Html\HtmlString;
use Nahid\Talk\Html\HtmlStringInterface;
use Nahid\Talk\Messages\Message;
use Nahid\Talk\Tests\TestCase;

class MessageTest extends TestCase
{
    /**
     * @var  Message
     */
    private $message;
    
    public function setUp()
    {
        parent::setUp();
        $this->message = new Message(['message' => 'test message']);
    }
    
    public function tearDown()
    {
        $this->message = null;
        parent::tearDown();
    }

    public function testIsHtmlable()
    {
        $this->assertInstanceOf(HtmlStringInterface::class, $this->message);
    }
    
    public function testToHtmlString()
    {
        $this->assertInstanceOf(HtmlString::class, $this->message->toHtmlString());
    }
}
