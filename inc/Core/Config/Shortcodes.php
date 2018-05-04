<?php
namespace imbaa\Affilipus\Core\Config;

use \imbaa\Affilipus\Core\Shortcodes AS Shortcode;

class Shortcodes {

    public static function init(){


        Shortcode\AffilipusRaw::init();
        Shortcode\AffilipusTopseller::init();


    }



}