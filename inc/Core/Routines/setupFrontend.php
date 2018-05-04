<?php

namespace imbaa\Affilipus\Core\Routines;


/**
 * Configuration which shoul be done in Front- and in Backend.
 *
 */

class setupFrontend {


	function __construct(){


        new \imbaa\Affilipus\Core\Filter\Comments();
        new \imbaa\Affilipus\Core\Filter\ProductPage();
        new \imbaa\Affilipus\Core\Config\Stylesheets();

    }

}