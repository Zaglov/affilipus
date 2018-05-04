<?php

namespace imbaa\Affilipus\Core\Affiliates\Zanox;

class Cron {



    function __construct(){

        register_activation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'activate_crons'));
        register_deactivation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'deactivate_crons'));


        if (!wp_next_scheduled ( 'imbaf_zanox_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_zanox_hourly' );

        }

        add_action('imbaf_zanox_hourly', array($this,'hourly'));

    }


    function activate_crons() {

        if (!wp_next_scheduled ( 'imbaf_zanox_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_zanox_hourly' );

        }

    }


    function deactivate_crons(){

        wp_clear_scheduled_hook('imbaf_zanox_hourly');

    }

    function hourly() {

        $p = new Zanox();
        $p -> refetchPrices();

    }


}