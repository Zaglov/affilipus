<?php
namespace imbaa\Affilipus\Core\Config;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Admin as ADMIN;
class Activation {

    function __construct(){

        add_action( 'init', array($this,'imbaf_config'));

        register_activation_hook( IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'imbaf_activate') );
        register_deactivation_hook( IMBAF_PLUGIN_PATH.'affilipus.php', array($this,'imbaf_deactivate'));

        add_action( 'activated_plugin', array($this,'imbaf_redirection_activate') );



    }

    /** Redirects user to license screen if no license can be found on activation */

    function imbaf_redirection_activate($plugin){


        if(get_option('imbaf_license_key') == null){

            if( $plugin == plugin_basename(IMBAF_PLUGIN_PATH.'affilipus.php') ) {
                exit( wp_redirect( admin_url( 'admin.php?page=imbaf_settings_license' ) ) );
            }

        }

    }


    function imbaf_activate() {

        require_once(IMBAF_PLUGIN_PATH.'/check_compatibility.php');



        if(!file_exists(IMBAF_CONTENT_FOLDER)){wp_mkdir_p(IMBAF_CONTENT_FOLDER);}
        if(!file_exists(IMBAF_CONTENT_FOLDER.'/templates_c')){wp_mkdir_p(IMBAF_CONTENT_FOLDER.'/templates_c');}
        if(!file_exists(IMBAF_CONTENT_FOLDER.'/templates_cache')){wp_mkdir_p(IMBAF_CONTENT_FOLDER.'/templates_cache');}
        if(!file_exists(IMBAF_CONTENT_FOLDER.'/templates_custom')){wp_mkdir_p(IMBAF_CONTENT_FOLDER.'/templates_custom');}

        $data = check_imbaf_compatibility();

        if($data['critical_activation_errors'] > 0){


            echo "Bei der Aktivierung von Affilipus sind ".$data['critical_activation_errors']." Fehler aufgetreten.<br>
				Bitte überprüfe die Kompitibilität deines Servers <a href='".IMBAF_PLUGIN_URL.'debug.php'."' target='_blank'><strong>hier</strong></a> bevor, du versuchst Affilipus erneut zu aktivieren.";

            echo "<h4>Folgende Fehler sind aufgetreten</h4>";

            echo implode("<br>",$data['critical_activation_messages']);
            die();

        }

        $this -> imbaf_config();

        $api = new CORE\API\affilipusAPI();

        $api->call('license','check');

        $templates = new ADMIN\imbafTemplates();
        $templates -> restore_secured_templates();



    }

    function imbaf_deactivate($plugin){

        $templates = new ADMIN\imbafTemplates();

        $templates -> secure_templates();

    }

    function imbaf_config(){


        $last_version = get_option('imbaf_current_version');

        if($last_version == null){

            add_option('imbaf_current_version',IMBAF_VERSION);

        } if ($last_version == IMBAF_VERSION){


            return false;


        }
        else if ($last_version != IMBAF_VERSION) {


            require_once(IMBAF_LIBRARY.'/smarty/' . 'Smarty.class.php');

            $smarty = new \Smarty();

            $compile_path = IMBAF_TEMPLATE_COMPILE_PATH;
            $smarty->error_reporting = false;

            if(is_writable($compile_path)){

                $smarty->setCompileDir($compile_path);

                if(is_writable(IMBAF_TEMPLATE_CACHE_PATH) && get_option('imbaf_enable_smarty_caching') == 1){

                    /*$smarty->cache_lifetime = 3600;
                    $smarty->caching = true;
                    $smarty->setCacheDir(IMBAF_TEMPLATE_CACHE_PATH);
                    $smarty->compile_check = true;*/

                }

            }

            //$smarty->clearAllCache();
            //$smarty->clearCompiledTemplate();

            update_option('imbaf_current_version',IMBAF_VERSION);

        }

        //flush_rewrite_rules();

        $options = [

            'autoload' => [

                'imbaf_enable_product_pages' => 1,
                'imbaf_enable_price_missing_email' => 1,
                'imbaf_display_products_in_loop' => 0,
                'imbaf_prefer_cdn_pictures' => 1,
                'imbaf_products_slug' => 'products',
                'imbaf_brands_slug' => 'brands',
                'imbaf_types_slug' => 'product-types',
                'imbaf_tags_slug' => 'product-tags',
                'imbaf_display_styles' => 1,
                'imbaf_load_google_fonts' => 1,
                'imbaf_display_shadows' => 1,
                'imbaf_execute_shortcodes_in_comments' => 1,
                'imbaf_enable_smarty_caching' => 0,
                'imbaf_smarty_cache_lifetime' => 5,
                'imbaf_enable_price_fallback' => 1,
                'imbaf_enable_cross_price' => 1

            ],
            'default' => [
                'imbaf_default_template' => "[affilipus_user_description]\r\n[affilipus_feature_list title=\"Features\"]\r\n[affilipus_price_list]",
                'imbaf_import_post_thumbnails' => 1,
                'imbaf_import_product_pictures' => 0
            ]
        ];


        if(get_option('imbaf_default_template') == null){

            add_option('imbaf_default_template',"[affilipus_user_description]\r\n[affilipus_feature_list title=\"Features\"]\r\n[affilipus_price_list]",'',true);

        }



        foreach($options['autoload'] as $option => $default_value){

            if(get_option($option) == null){add_option($option,$default_value,'',true);}

        }


        foreach($options['default'] as $option => $default_value){

            if(get_option($option) == null){add_option($option,$default_value,'',false);}

        }


        if(get_option('imbaf_current_version') != IMBAF_VERSION){

            $this -> imbaf_config();

            global $wpdb;

            $wpdb -> query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_imbaf_item_weight_unit';");
            $wpdb -> query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_imbaf_ean' AND meta_value = 'none';");
            $wpdb -> query("DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_imbaf_ean' AND meta_value = '';");

            wp_cache_flush();

        }


        return true;

    }

}