<?php

namespace Nahid\Talk\Tests;

use GrahamCampbell\TestBenchCore\ServiceProviderTrait;
use Nahid\Talk\Talk;

/**
 * This is the service provider test class.
 */
class TalkServiceProviderTest extends TestCase
{
    use ServiceProviderTrait;

    public function testTalkIsInjectable()
    {
        $this->assertIsInjectable(Talk::class);
    }
}
