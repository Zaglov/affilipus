<?php

/*
Plugin Name: Affilipus
Description: Affilipus - Affiliate Marketing Made Easy
Version: 1.9.2
Text Domain: imb_affiliate
*/

define( 'IMBAF_VERSION', '1.9.2' );

use imbaa\Affilipus\Entities\AffilipusProduct;
use imbaa\Affilipus\Entities\AffilipusShop;


if(date_default_timezone_get()!=ini_get('date.timezone')){

    $timezone = ini_get('date.timezone');

    if($timezone != '') {

        date_default_timezone_set($timezone);
    }

}


function add_post_types_to_loop($query) {

    if ($query->is_main_query() && $query->is_front_page()) {

        $query->set('post_type', array('post', 'imbafproducts'));

    }

}

if(get_option('imbaf_display_products_in_loop') == 1){

    add_action('pre_get_posts', 'add_post_types_to_loop');

}


// This wrapper prevents affilipus from crashing on affilipus.com when license information is retrieved
if ( ! isset( $_GET['edd_action'] ) ) {


    if ( ! defined( 'ABSPATH' ) ) {
        die( 'Forbidden' );
    }


    require_once( 'constants.php' );



    if(AFP_DEBUG == true) {

        error_reporting(E_ALL);
        ini_set('display_errors',1);

    }


    require dirname( __FILE__ ) . '/inc/Core/Autoload/Psr_4.php';
    $autoload_class_name = '\imbaa\Affilipus\Core\Autoload\Psr_4';

    $autoload = new $autoload_class_name();
    $autoload->add( 'imbaa\Affilipus', dirname( __FILE__ ) . '/inc' );

    spl_autoload_register( array( $autoload, 'autoload' ) );

    new imbaa\Affilipus\Core\Routines\setupCommon();

    if(!is_admin()){

        new imbaa\Affilipus\Core\Routines\setupFrontend();

    } else {

    }

    function init_admin(){


        $current_user = wp_get_current_user();


        if (in_array('administrator', $current_user->roles)) {


            if (is_admin()) {

                new imbaa\Affilipus\Core\Routines\setupAdmin();

            }


        }

    }

    add_action('init','init_admin');



}


function register_hidden_post_status(){
    register_post_status( 'hidden', array(
        'label'                     => _x( 'Hidden', 'post' ),
        'public'                    => false,
        'exclude_from_search'       => true,
        'show_in_admin_all_list'    => false,
        'show_in_admin_status_list' => false,
        'label_count'               => _n_noop( 'Unread <span class="count">(%s)</span>', 'Unread <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'register_hidden_post_status' );


function my_custom_post_status(){
    register_post_status( 'hidden', array(
        'label'                     => _x( 'Temporär', 'post' ),
        'public'                    => false,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Temporär <span class="count">(%s)</span>', 'Temporär <span class="count">(%s)</span>' ),
    ) );
}
add_action( 'init', 'my_custom_post_status' );


