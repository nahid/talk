<?php

namespace Nahid\Talk\Html;


use Embera\Embera;
use Illuminate\Contracts\Support\Htmlable;
use Nahid\Talk\Embera\Adapter;

class HtmlString implements Htmlable
{
    /**
     * @var string
     */
    protected $string;

    /**
     * @var Embera
     */
    protected $driver;

    /**
     * HtmlString constructor.
     *
     * @param $string
     */
    public function __construct($string)
    {
        $this->driver = new Embera(
            [
                'http' => [
                    'curl' => [CURLOPT_SSL_VERIFYPEER => false]
                ]
            ]
        );
        $this->string = $string;
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        if (config('talk.oembed.enabled')) {
            $parsedUrl = parse_url(config('talk.oembed.url'));
            $this->driver->addProvider(
                $parsedUrl['host'],
                Adapter::class,
                [
                    'api_key' => config('talk.oembed.key'),
                ]
            );
        }

        $result = $this->driver->autoEmbed($this->string);

        return $result;
    }
}