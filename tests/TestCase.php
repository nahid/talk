<?php
class TestCase extends Orchestra\Testbench\TestCase
{

    public function setUp()
    {
        parent::setUp();
    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->visit('/')
             ->see('Laravel 5');
    }
}
