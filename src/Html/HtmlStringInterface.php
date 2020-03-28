<?php
/**
 * Created by PhpStorm.
 * User: claudiopinto
 * Date: 26/10/2018
 * Time: 15:57
 */

namespace Nahid\Talk\Html;


use Illuminate\Contracts\Support\Htmlable;

interface HtmlStringInterface
{
    /**
     * @return Htmlable
     */
    public function toHtmlString();
}
