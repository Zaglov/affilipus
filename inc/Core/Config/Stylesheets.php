<?php

namespace imbaa\Affilipus\Core\Config;


class Stylesheets {


    function __construct(){


        add_action('wp_enqueue_scripts', array($this, 'stylesheets'));




    }


    function stylesheets(){

        if(get_option('imbaf_display_styles') != 0){

            wp_enqueue_style( 'imbaf-widgets', IMBAF_PLUGIN_URL.'css/common/widgets.css' ,false, IMBAF_VERSION);
            wp_enqueue_style( 'imbaf-formats', IMBAF_PLUGIN_URL.'css/common/formats.css',false, IMBAF_VERSION);
            wp_enqueue_style( 'imbaf-buttons', IMBAF_PLUGIN_URL.'css/common/buttons.css',false, IMBAF_VERSION);
            wp_enqueue_style( 'imbaf-comptable-values', IMBAF_PLUGIN_URL.'css/common/comptable-values.css',false, IMBAF_VERSION);
            wp_enqueue_style( 'star-rating', IMBAF_PLUGIN_URL . 'css/common/star-rating.css',false, IMBAF_VERSION);

        }


        wp_enqueue_style( 'font-awesome', IMBAF_PLUGIN_URL . 'libs/font-awesome/css/font-awesome.css',false,IMBAF_VERSION);

    }

}