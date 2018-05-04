<?php
/**
 * Gemeinsame Funktionen die von den Shortcodes verwendet werden
 */

namespace imbaa\Affilipus\Core\Output;
use imbaa\Affilipus\Core as CORE;


class imbafShortcodeBasics extends imbafShortcodeDefinitions {


    public static function getShortcodeParams($shortcode){


        $s = new CORE\Output\imbafShortcodes();

        $params = call_user_func_array(array($s,$shortcode['callback']), array(array('get_params' => true)));

        return $params;


    }

    function afpfc(){}

    function register_plugin_styles(){

        if ( get_option( 'imbaf_display_styles' ) == 1 ) {


            wp_register_style( 'imbaf-buttons', IMBAF_PLUGIN_RELATIVE_URL . 'css/common/buttons.css',false,IMBAF_VERSION);


            if(get_option('imbaf_display_shadows')){
                wp_register_style( 'imbaf-shadows', IMBAF_PLUGIN_RELATIVE_URL . 'css/common/shadows.css',false,IMBAF_VERSION );
            }



            wp_register_style( 'imbaf-tables', IMBAF_PLUGIN_RELATIVE_URL . 'css/common/tables.css',false,IMBAF_VERSION );
            wp_register_style( 'imbaf-formats', IMBAF_PLUGIN_RELATIVE_URL . 'css/common/formats.css',false,IMBAF_VERSION );
            wp_register_style( 'font-awesome', IMBAF_PLUGIN_RELATIVE_URL . 'libs/font-awesome/css/font-awesome.css',false,IMBAF_VERSION);

        }

        if ( get_option( 'imbaf_load_google_fonts' ) == 1 ) {

            wp_register_style( 'open-sans', '//fonts.googleapis.com/css?family=Open+Sans:400,300,700',false,IMBAF_VERSION);

        }

    }

    // Daten f�r das Produkt vorbereiten


    function prepare_data($id = null)
    {

        $this->p = new CORE\Affiliates\affiliateProduct();


        if($id != null) {


            $this -> product = $this -> p -> loadProductById($id);

        } else {


            if ($this->post_id == null) {
                $this->post_id = $this -> get_product_id();
            }


            $this->product = $this->p->loadProductById($this->post_id);

        }



        return $this -> product;




    }

    // Template laden

    function sideload_template($template,$default_template, $args,$data){


        $folders = array(

            IMBAF_CONTENT_FOLDER,
            IMBAF_CUSTOM_TEMPLATES,
            IMBAF_TEMPLATE_COMPILE_PATH,
            IMBAF_TEMPLATE_CACHE_PATH

        );


        foreach($folders as $folder){



            if(!file_exists($folder)){

                wp_mkdir_p($folder);

            }


        }

        $default_path = IMBAF_PLUGIN_PATH.'templates/'.$default_template.'.tpl';

        $custom_path = IMBAF_CUSTOM_TEMPLATES.'/'.$template.'.tpl';

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        if(file_exists($custom_path)){

            $fullpath = $custom_path;

        } else {

            $fullpath = $default_path;

        }

        require_once(IMBAF_LIBRARY.'/smarty/' . 'Smarty.class.php');

        $smarty = new \Smarty();

        $compile_path = IMBAF_TEMPLATE_COMPILE_PATH;
        $smarty->error_reporting = false;


        if(is_writable($compile_path)){

            $smarty->setCompileDir($compile_path);
            //$smarty->force_compile = true;

            $smarty -> assign('args',$args);
            $smarty -> assign('data',$data);
            $smarty -> assign('IMBAF_IMAGES',IMBAF_IMAGES);

            if(is_writable(IMBAF_TEMPLATE_CACHE_PATH) && get_option('imbaf_enable_smarty_caching') == 1){

                //$smarty->cache_lifetime = 3600;
                //$smarty->caching = true;
                //$smarty->setCacheDir(IMBAF_TEMPLATE_CACHE_PATH);
                //$smarty->compile_check = true;

            }



            $template = '<!-- begin: '.str_replace($_SERVER['DOCUMENT_ROOT'],'',$fullpath).'-->';
            $template .= $smarty -> fetch($fullpath,md5(serialize($args).serialize($data)));
            $template .= '<!-- end: '.str_replace($_SERVER['DOCUMENT_ROOT'],'',$fullpath).'-->';

            $template = str_replace(array("\r\n","\n","\r"),array('','',''),$template);

            $template = "<div class='imbaf-template-container'>".$template."</div>";

            return $template;

        }

        else {


            if ( is_user_logged_in() ) {

                if( current_user_can( 'manage_options' ) ) {


                    return 'Fehler beim Laden des Templates. Bitte stelle sicher, dass der Ordner '.$compile_path.' die n�tigen Schreibrechte hat. Diese Meldung kannst nur du als Admin sehen.';


                } else {


                    return 'Fehler beim laden des Templates.';

                }



            }


        }



    }

    function sideload_stylesheet($path,$slug){


        $custom_path = IMBAF_CUSTOM_TEMPLATES.'/'.$path.'.css';
        $default_path = IMBAF_TEMPLATES.'/'.$path.'.css';




        if(file_exists($custom_path)){

            $fullpath = IMBAF_CONTENT_RELATIVE_URL.'/templates_custom/'.$path.'.css';
            $custom = true;
            $load = true;

        }

        else if(file_exists($default_path)) {

            $fullpath = IMBAF_PLUGIN_RELATIVE_URL.'templates/'.$path.'.css';
            $custom = false;
            $load = true;

        } else {

            $fullpath = false;
            $load = false;

        }





        if( $load == true && ($custom == true || get_option('imbaf_display_styles') != 0)) {

            //wp_register_style($slug,$fullpath);





            wp_enqueue_style($slug,$fullpath,false,IMBAF_VERSION);

            /*echo "<pre>";

            echo $slug."\r\n";
            echo $fullpath;

            echo "</pre>";*/


        }

        return $fullpath;

    }

    function handle_defaults($default_args){


        $args = array();

        foreach($default_args as $arg => $data){


            if(isset($data['wp_option']) && get_option($data['wp_option'])){

                $data['value'] = get_option($data['wp_option']);

            }


            if(isset($data['value'])){


                $args[$arg] = $data['value'];

            } else {
                $args[$arg] = null;

            }





        }


        return $args;


    }

    function handle_true_false($args){


        foreach($args as &$arg){

            if($arg === 'false'){$arg = 'no';}
            else if($arg === 'true'){$arg = 'yes';}

            if($arg === 'yes'){$arg = true;}
            else if ($arg === 'no'){$arg = false;}

        }

        return $args;

    }

    function get_defaults($shortcode){

        $defaults = @call_user_func_array( array(
            $this,
            $shortcode['callback']
        ), array( array( 'get_params' => true ) ) );;



        if(is_array($defaults) && count($defaults) != 0){

            foreach($defaults as &$d){


                if(is_array($d)){

                    if(!isset($d['internal'])){$d['internal'] = false;}
                    if(!isset($d['description'])){$d['description'] = null;}
                    if(!isset($d['description_long'])){$d['description_long'] = null;}
                    if(!isset($d['type'])){$d['type'] = 'input';}

                }

            }

        }



        return $defaults;

    }

    function get_configurable_shortcodes(){


        $shortcodes = $this::$shortcodes;

        $strip = array('product');


        foreach ( $shortcodes as $code => &$shortcode ) {


            if ( $shortcode['public'] == 1 ) {

                $shortcode['params'] = @$this->get_defaults( $shortcode );

                if ( count( $shortcode['params'] ) != 0 ) {


                    foreach ( $shortcode['params'] as $paramkey => $param ) {

                        if (! isset( $param['wp_option']) || in_array( $param['type'], $strip  ) ) {

                            unset( $shortcode['params'][ $paramkey ] );

                        }

                    }
                }

                if ( count( $shortcode['params'] ) == 0 ) {

                    unset( $shortcodes[ $code ] );

                }

            } else {

                unset( $shortcodes[ $code ] );

            }

        }

        return $shortcodes;

    }


}