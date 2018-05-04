<?php

namespace imbaa\Affilipus\Core\Routines;
use imbaa\Affilipus\Core\API;
use \imbaa\Affilipus\Core\Config\Shortcodes as Shortcode;

/**
 * Configuration which should be done in Front- and in Backend.
 *
 */

class setupCommon {


	function __construct(){



        if(isset($_GET['afpping']) && $_GET['afpping'] == 1) {


            $api = new API\affilipusAPI();
            $license = $api -> checkLicense(array('cached' => false));

            unset($license['customer_email']);
            unset($license['customer_name']);
            unset($license['payment_id']);
            unset($license['activations_left']);
            unset($license['beta']);

            $license['current_afp_version'] = IMBAF_VERSION;

            header('Content-Type: text/json');
            echo json_encode($license);

            die();

        }


		new \imbaa\Affilipus\Core\Config\Activation();
		new \imbaa\Affilipus\Core\Config\PostTypes();
		new \imbaa\Affilipus\Core\Config\Taxonomies();
		new \imbaa\Affilipus\Core\Output\imbafShortcodes(true);
		new \imbaa\Affilipus\Core\Widgets\buyWidget();
        new \imbaa\Affilipus\Admin\Config\imbafCron();
        new \imbaa\Affilipus\Core\API\AsyncProductData();



        add_action('init',[Shortcode::class,'init']);



    }

}