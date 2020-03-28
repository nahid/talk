<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 26/10/2018
 * Time: 16:01
 */

namespace Nahid\Talk\Embera;


use Embera\Adapters\Service;

/**
 * Class Adapter
 *
 * @package Nahid\Talk\Embera
 */
class Adapter extends Service
{
    protected $config;

    /**
     * Adapter constructor.
     *
     * @param string $url
     * @param array  $config
     * @param        $oembed
     */
    public function __construct($url, array $config = array(), $oembed)
    {
        $this->apiUrl = config('talk.oembed.url');
        parent::__construct($url, $config, $oembed);
    }

    /**
     * Validates that the url belongs to this service.
     * Should be implemented on all children and should
     * return a boolean (preg_match returns 0 or 1 that
     * is why I'm also allowing 'int' as a return type).
     *
     * The current url is made available via $this->url
     *
     * @return bool|int
     */
    protected function validateUrl()
    {
        $parsedUrl = parse_url($this->apiUrl);

        return preg_match('#' . $parsedUrl['host'] . '#i', (string)$this->url);
    }
}
