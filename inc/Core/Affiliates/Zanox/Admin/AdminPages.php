<?php

namespace imbaa\Affilipus\Core\Affiliates\Zanox\Admin;
use imbaa\Affilipus\Core as CORE;

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class AdminPages {


    var $client = null;
    var $logfile = '';

    public function __construct(){

        add_action( 'admin_menu', array( $this, 'setup_menu' ) );

        $this -> client = new \imbaa\Affilipus\Core\Affiliates\Zanox\Zanox();

    }

    public function setup_menu(){

        add_submenu_page('imbaf_partner','Zanox', 'Zanox', 'administrator','imbaf_partner_zanox', array($this,'admin_page'));
        add_action( 'admin_init', array($this,'register_settings') );

    }

    public function register_settings() {

        //register our settings

        register_setting( 'imbaf_partner_zanox', 'imbaf_zanox_connect_id' );
        register_setting( 'imbaf_partner_zanox', 'imbaf_zanox_secret_key' );

    }

    public function log($text){


        $this -> logfile .= date('H:i:s',time()).": ".$text."\r\n";

    }

    // Page Output

    public function admin_page() {


        ?> <div class="wrap"> <?php

            $api = new CORE\API\affilipusAPI();
            if(!$api->allowAction(true)){die();}

            if(!isset($_GET['action'])){

                $_GET['action'] = null;

            }


            if($_GET['action'] == null){





                    $_GET['action'] = 'import_product';



            }

            if(get_option('imbaf_zanox_connect_id') == '' || get_option('imbaf_zanox_secret_key') == ''){


                $_GET['action'] = 'settings';

            }


            $action = $_GET['action'];


            $this -> tabbed_navigation();

            switch($action){


                case 'settings':

                    $this->settings_page();


                    break;

                case 'import_product':

                   $this -> search_page();

                    break;


                case 'import_price':

                    $this->import_price_page();

                    break;

                case 'test_call':


                    $this -> test_call_page();

                    break;


                case 'debug':

                    $this -> debug_page();

                    break;

                default:


                    $this->settings_page();

                    break;


            }

            ?> </div> <?php



    }

    public function tabbed_navigation(){




        ?>

        <h2>Zanox Partnerprogramm</h2>

        <h2 class="nav-tab-wrapper">

            <a
                class="nav-tab <?php if($_GET['action'] == 'settings' || !isset($_GET['action'])) {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_zanox&action=settings">
                Einstellungen
            </a>

            <?php if(get_option('imbaf_zanox_connect_id') != '' && get_option('imbaf_zanox_secret_key') != ''){ ?>

            <a
                class="nav-tab <?php if($_GET['action'] == 'import_product') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_zanox&action=import_product">
                Produktimport
            </a>


            <a
                class="nav-tab <?php if($_GET['action'] == 'import_price') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_zanox&action=import_price">
                Preisimport
            </a>



            <a
                class="nav-tab <?php if($_GET['action'] == 'test_call') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_zanox&action=test_call">
                API Test
            </a>

            <?php } ?>



            <?php


            if(defined('AFP_DEBUG') && AFP_DEBUG == true){

                ?>

                <a
                    class="nav-tab <?php if($_GET['action'] == 'debug') {?>nav-tab-active <?php } ?>"
                    href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_zanox&action=debug">
                    Debug
                </a>

                <?php


            }


            ?>



        </h2>


        <?php



    }

    public function settings_page(){



        ?>

        <form method="post" action="options.php" >
            <?php  settings_fields( 'imbaf_partner_zanox' );?>
            <?php  do_settings_sections( 'imbaf_partner_zanox' );?>

            <table class="form-table widefat striped" style="max-width:600px;">

                <tr>

                    <td>ConnectId</td>
                    <td><input type="text" class="widefat" name="imbaf_zanox_connect_id" value="<?php if(get_option('imbaf_zanox_connect_id')){echo get_option('imbaf_zanox_connect_id');} ?>"></td>

                </tr>


                <tr>

                    <td>SecretKey</td>
                    <td><input type="password" class="widefat" name="imbaf_zanox_secret_key" value="<?php if(get_option('imbaf_zanox_secret_key')){echo get_option('imbaf_zanox_secret_key');} ?>"></td>

                </tr>


                <tr>


                    <td></td>
                    <td><a href="https://affilipus.com/zanox/" class="button" target="_blank">Hilfe</a></td>

                </tr>


            </table>


            <?php submit_button(); ?>

            <h2>Tutorial</h2>

            <iframe width="600" height="388" src="https://www.youtube.com/embed/dCCQNRKyRCM?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>




        </form>


        <?php





    }

    public function search_page(){


        wp_enqueue_style('imbaf-admin');
        wp_enqueue_script( 'imbaf_search');
        wp_localize_script(
            'imbaf_search',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'partner' => 'zanox'
            )
        );


        $spacesList = $this->client -> getSpacesList(false);



        $tpl = new \imbaa\Affilipus\Admin\Utilities\adminTemplates();


        $tpl -> smarty -> assign('spacesList',$spacesList);
        $tpl -> smarty -> display('zanox/search.tpl');

    }

    public function import_price_page(){


        ?>

        <p>Ich rufe automatisch jede Stunde so viele Preise, wie dein Server mich lässt, über den wp-cron für dich ab. Du kannst aber auch einen eigenen Cronjob einrichten, mit dem du die Preise häufiger abrufen kannst.</p>

        <?php

        $url = IMBAF_PLUGIN_URL.'cron.php?partner=zanox&secret='.md5(NONCE_KEY.$_SERVER['HTTP_HOST']);


        $cron_info = get_option('imbaf_cron_zanox_refetch_prices_status');


        if($cron_info != null){

            echo "<p>Der Cronjob wurde zuletzt am ".date('d.m.Y',$cron_info['start'])." um ".date('H:i',$cron_info['start'])." ausgeführt. Ich habe {$cron_info['products_updated']} Produkt(e) aktualisiert. Das hat {$cron_info['duration']} Sekunden gedauert.</p>";

        }


        echo "<pre>";

        echo 'Deine Cronjob URL lautet: '.$url;

        echo "</pre>";


    }

    public function test_call_page(){

        echo "<h2>Zanox API Test</h2>";

        $beginn = microtime(true);

        $this -> log("Versuche Produkte von Zanox abzurufen.");

        $spaces = $this-> client -> getSpacesList(false);

        $spaces = array_values($spaces);

        shuffle($spaces);



        $products = $this -> client -> productSearch(['term' => '', 'shop' => $spaces[0]['id']]);
        
        


        if($spaces != false){

            $this -> log("<strong style=\"color: green;\">API Verbindung erfolgreich.</strong>");


        } else {

            $this -> log("<strong style=\"color:red;\">API Verbindung nicht erfolgreich.</strong>");

        }


        $dauer = round(microtime(true) - $beginn,2);

        $this -> log("Verarbeitungszeit {$dauer} Sek.");

        echo "<pre>";
        echo $this -> logfile;
        echo "</pre>";


        return false;

    }

    public function debug_page(){

        $beginn = microtime(true);

        echo "<br>Hier sollte die DEBUG-Routine untergebracht werden.</br>";

        $dauer = round(microtime(true) - $beginn,2);
        echo "Verarbeitung des Skripts: $dauer Sek.";

    }

}

