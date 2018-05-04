<?php

namespace imbaa\Affilipus\Core\Affiliates\Affilinet;

class Cron {



    function __construct(){

        //register_activation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'activate_crons'));
        register_deactivation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'deactivate_crons'));


        if (!wp_next_scheduled ( 'imbaf_affilinet_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_affilinet_hourly' );

        }


        add_action('imbaf_affilinet_hourly', array($this,'hourly'));
    }


    function activate_crons() {

        if (!wp_next_scheduled ( 'imbaf_affilinet_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_affilinet_hourly' );

        }


    }


    function deactivate_crons(){

        wp_clear_scheduled_hook('imbaf_daily_event');
        delete_option('imbaf_current_version');

    }

    function hourly() {

        $p = new Affilinet();
        $p -> refetchPrices();

    }


}