<?php
namespace imbaa\Affilipus\Core\Config;
use imbaa\Affilipus\Core as CORE;

class affilipussy{


    function __construct(){

return;

        add_action('wp_ajax_imbaf_check_license', array($this,'check_license') );
        add_action('wp_ajax_nopriv_imbaf_check_license', array($this,'check_license') );
        add_action('wp_enqueue_scripts',[$this,'afp_scripts']);

    }


    function check_license(){

        $api = new CORE\API\affilipusAPI();
        $license = $api -> checkLicense(['cached' => true]);


        if($license['item_name'] == 'Affilipussy'){

            echo $license['license'];

        }


        wp_die();

    }


    function afp_scripts(){


        return;

        $api = new CORE\API\affilipusAPI();
        $license = $api -> checkLicense(['cached' => true]);



        if($license != 'missing' || $license['item_name'] == 'Affilipussy') {


            wp_register_script('imbaf-pussy', IMBAF_PLUGIN_URL . 'js/pussy.js', array('jquery'), '1.0', true);

            wp_enqueue_script('imbaf-pussy');

            wp_localize_script(
                'imbaf-pussy',
                'ajax_object',
                array(
                    'ajax_url' => admin_url('admin-ajax.php')
                )
            );

        }




    }


}