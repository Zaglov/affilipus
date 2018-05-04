<?php

namespace imbaa\Affilipus\Admin\Config;
use imbaa\Affilipus\Core\Affiliates as Affiliate;



class imbafCron {

	function __construct(){

		register_activation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'activate_crons'));
		register_deactivation_hook(IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'deactivate_crons'));

        new \imbaa\Affilipus\Core\Affiliates\Zanox\Cron();
        new \imbaa\Affilipus\Core\Affiliates\Affilinet\Cron();
        new \imbaa\Affilipus\Core\Affiliates\Amazon\Cron();

        if (! wp_next_scheduled ( 'imbaf_hourly_event' )) {

            wp_schedule_event( time(), 'hourly', 'imbaf_hourly_event' );

        }

        add_action('imbaf_hourly_event', array($this,'cron_imbaf_hourly'));

	}

	function activate_crons() {

		if (! wp_next_scheduled ( 'imbaf_hourly_event' )) {

			wp_schedule_event( time(), 'hourly', 'imbaf_hourly_event' );

		}

	}

	function deactivate_crons(){

		$p = new Affiliate\Amazon\partnerAmazon();
		$p -> flushTopsellerData();

		wp_clear_scheduled_hook('imbaf_daily_event');
		delete_option('imbaf_current_version');

	}

	function cron_imbaf_hourly() {

        do_action('imbaf_flush_cache');

        $this -> temporary_data_garbage_collection();

        $p = new Affiliate\Amazon\partnerAmazon();

		$p -> refetchPrices();

	}

	function temporary_data_garbage_collection(){


	    global $wpdb;

        $partner = new \imbaa\Affilipus\Core\Affiliates\Amazon\partnerAmazon();
        $partner -> flushTopsellerData();

        $now = time();

        $query = "
            SELECT pm.meta_value AS expiration_time, pm2.meta_value AS product_group 
            FROM {$wpdb->postmeta} pm, {$wpdb->postmeta} pm2 
            WHERE pm.meta_key = '_imbaf_expires' 
            AND pm2.meta_key = '_imbaf_group' 
            AND pm.post_id = pm2.post_id
            AND pm.meta_value < {$now}
            GROUP BY product_group;";



        $groups = $wpdb -> get_results($query);




        if(count($groups) > 0){

            $partner = new \imbaa\Affilipus\Core\Affiliates\Amazon\partnerAmazon();


            foreach($groups as $group){

                $partner -> flushTopsellerData($group->product_group);

            }

        }

    }

}