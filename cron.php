<?php

require_once($_SERVER['DOCUMENT_ROOT'].'/wp/wp-load.php');

require_once('constants.php');

$secret = md5(NONCE_KEY.$_SERVER['HTTP_HOST']);


if($secret == $_GET['secret']){

	switch($_GET['partner']){


		case 'amazon':

			$p = new imbaa\Affilipus\Core\Affiliates\Amazon\partnerAmazon();
			$p -> refetchPrices(true);
			
		break;


        case 'affilinet':


            $p = new imbaa\Affilipus\Core\Affiliates\Affilinet\Affilinet();
            $p -> refetchPrices();

        break;

        case 'zanox':


            $p = new imbaa\Affilipus\Core\Affiliates\Zanox\Zanox();
            $p -> refetchPrices();

        break;

	}

}