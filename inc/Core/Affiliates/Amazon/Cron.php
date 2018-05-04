<?php

namespace imbaa\Affilipus\Core\Affiliates\Amazon;

class Cron {



    function __construct(){

        register_activation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'activate_crons'));
        register_deactivation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'deactivate_crons'));



        if (!wp_next_scheduled ( 'imbaf_amazon_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_amazon_hourly' );

        }

        add_action('imbaf_amazon_hourly', array($this,'hourly'));

    }


    function activate_crons() {

        if (!wp_next_scheduled ( 'imbaf_amazon_hourly' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_amazon_hourly' );

        }

    }


    function deactivate_crons(){

        wp_clear_scheduled_hook('imbaf_amazon_hourly');

    }

    function hourly() {

        $p = new partnerAmazon();
        $p -> refetchPrices();

    }


}