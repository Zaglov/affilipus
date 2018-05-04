<?php

namespace imbaa\Affilipus\Admin\Config;
use imbaa\Affilipus\Core as CORE;


class AffiliatePartner {


    function __construct(){



        $amazon = new \imbaa\Affilipus\Core\Affiliates\Amazon\partnerAmazon();
        $amazon -> setup();

        $affilinet = new \imbaa\Affilipus\Core\Affiliates\Affilinet\Affilinet();
        $affilinet -> setup();


        $zanox = new \imbaa\Affilipus\Core\Affiliates\Zanox\Zanox();
        $zanox -> setup();



        if(AFP_DEBUG == true ){

            $webgains = new \imbaa\Affilipus\Core\Affiliates\Webgains\Webgains();
            $webgains -> setup();


        }



    }

}