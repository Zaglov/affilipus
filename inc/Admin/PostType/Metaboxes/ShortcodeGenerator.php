<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ShortcodeGenerator {




    public static function metabox($post){

        wp_register_script( 'imbaf_clipboard', IMBAF_PLUGIN_URL  . 'js/libs/clipboard.min.js', array('jquery'), '1.0', true);
        wp_register_script( 'imbaf_admin_shortcode_generator', IMBAF_PLUGIN_URL  . 'js/shortcodeGenerator.js', array('jquery','knockout','knockout-viewmodel','imbaf_clipboard'), '1.0', true);

        wp_enqueue_script('imbaf_admin_shortcode_generator');
        wp_enqueue_script('imbaf_clipboard');


        wp_localize_script(
            'imbaf_admin_shortcode_generator',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );

        echo file_get_contents(IMBAF_PLUGIN_PATH.'/dev/generator.tpl');

    }


    public static function save($post_id){



      

    }







}