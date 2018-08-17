<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 31/10/2018
 * Time: 10:43
 */

namespace Nahid\Talk\Tests\Mocks;


use Embera\Embera;
use Embera\HttpRequest;

class EmberaMock extends Embera
{
    public function __construct(array $config = array(), $response = [])
    {
        parent::__construct($config);
        $mockBuilder = new \PHPUnit_Framework_MockObject_Generator();
        $mockHttp = $mockBuilder->getMock(HttpRequest::class);
        $mockHttp->method('fetch')
            ->willReturn(json_encode($response));
        
        $this->oembed = new \Embera\Oembed($mockHttp);
        $this->providers = new \Embera\Providers($this->config, $this->oembed);
    }
}
