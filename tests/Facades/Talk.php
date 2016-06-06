<?php

namespace Nahid\Talk\Tests\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use Nahid\Talk\Tests\TestCase;

/**
 * This is the Talk facade test class.
 */
class Talk extends TestCase
{
    use FacadeTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'talk';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return \Nahid\Talk\Facades\Talk::class;
    }

    /**
     * Get the facade root.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return \Nahid\Talk\Talk::class;
    }
}
