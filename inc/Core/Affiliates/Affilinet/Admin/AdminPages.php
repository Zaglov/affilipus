<?php

namespace imbaa\Affilipus\Core\Affiliates\Affilinet\Admin;
use imbaa\Affilipus\Core as CORE;

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class AdminPages {


    var $client = null;
    var $logfile = '';

    public function __construct(){

        add_action( 'admin_menu', array( $this, 'setup_menu' ) );

        $this -> client = new \imbaa\Affilipus\Core\Affiliates\Affilinet\Affilinet();

    }

    public function setup_menu(){

        add_submenu_page('imbaf_partner','Affilinet', 'Affilinet', 'administrator','imbaf_partner_affilinet', array($this,'admin_page'));
        add_action( 'admin_init', array($this,'register_settings') );

    }

    public function register_settings() {

        //register our settings

        register_setting( 'imbaf_partner_affilinet', 'imbaf_affilinet_user' );
        register_setting( 'imbaf_partner_affilinet', 'imbaf_affilinet_publisher_id' );
        register_setting( 'imbaf_partner_affilinet', 'imbaf_affilinet_password' );
        register_setting( 'imbaf_partner_affilinet', 'imbaf_affilinet_product_webservice_password' );
        register_setting( 'imbaf_partner_affilinet', 'imbaf_affilinet_publisher_webservice_password' );

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



                 if(get_option('imbaf_affilinet_publisher_id') == '' || get_option('imbaf_affilinet_product_webservice_password') == ''){

                    $_GET['action'] = 'settings';

                } else {

                    $_GET['action'] = 'import_product';

                }


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

        <h2>Affilinet Partnerprogram</h2>

        <h2 class="nav-tab-wrapper">

            <a
                class="nav-tab <?php if($_GET['action'] == 'settings' || !isset($_GET['action'])) {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_affilinet&action=settings">
                Einstellungen
            </a>


            <a
                class="nav-tab <?php if($_GET['action'] == 'import_product') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_affilinet&action=import_product">
                Produktimport
            </a>


            <a
                class="nav-tab <?php if($_GET['action'] == 'import_price') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_affilinet&action=import_price">
                Preisimport
            </a>



            <a
                class="nav-tab <?php if($_GET['action'] == 'test_call') {?>nav-tab-active <?php } ?>"
                href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_affilinet&action=test_call">
                API Test
            </a>



            <?php


            if(defined('AFP_DEBUG') && AFP_DEBUG == true){

                ?>

                <a
                    class="nav-tab <?php if($_GET['action'] == 'debug') {?>nav-tab-active <?php } ?>"
                    href="<?php echo admin_url() ?>admin.php?page=imbaf_partner_affilinet&action=debug">
                    Debug
                </a>

                <?php


            }


            ?>



        </h2>


        <?php



    }

    public function settings_page(){

        delete_transient('imbaf_affilinet_shop_list');
        delete_transient('imbaf_affilinet_token');

        ?>

        <form method="post" action="options.php">
            <?php  settings_fields( 'imbaf_partner_affilinet' );?>
            <?php  do_settings_sections( 'imbaf_partner_affilinet' );?>

            
            <h2>Einstellungen</h2>
            
            <table class="form-table widefat striped" style="max-width:600px;">

                
                <tbody>
                <tr>

                    <td>Publisher Webservice User</td>
                    <td><input type="text" class="widefat" name="imbaf_affilinet_user" value="<?php if(get_option('imbaf_affilinet_user')){echo get_option('imbaf_affilinet_user');} ?>"></td>

                </tr>

                <tr>

                    <td>Publisher ID</td>
                    <td><input type="text" class="widefat" name="imbaf_affilinet_publisher_id" value="<?php if(get_option('imbaf_affilinet_publisher_id')){echo get_option('imbaf_affilinet_publisher_id');} ?>"></td>

                </tr>

                <tr>

                    <td>Produkt Webservice Passwort</td>
                    <td><input type="password" class="widefat" name="imbaf_affilinet_product_webservice_password" value="<?php if(get_option('imbaf_affilinet_product_webservice_password')){echo get_option('imbaf_affilinet_product_webservice_password');} ?>"></td>

                </tr>


                <tr>


                    <td></td>
                    <td><a href="https://affilipus.com/affilinet/" class="button" target="_blank">Hilfe</a></td>

                </tr>



                </tbody>

            </table>

            <?php submit_button(); ?>


            <h2>Tutorial</h2>
            
            <iframe width="600" height="338" src="https://www.youtube.com/embed/jC0QcrwYvbg?rel=0&amp;controls=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>


        </form>


        <?php





    }

    public function search_page(){

        if(!class_exists('\SoapClient')) {

            echo "Leider ist der SOAP Client nicht in PHP verfügbar. Bitte wende dich an deinen Anbieter oder informiere dich hier über die installation: http://php.net/manual/de/book.soap.php";
            return;

        }


        wp_enqueue_style('imbaf-admin');
        wp_enqueue_script( 'imbaf_search');
        wp_localize_script(
            'imbaf_search',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'partner' => 'affilinet'
            )
        );


        $shopList = $this->client -> getShopList(false);

        if(!array_key_exists(1,$shopList->Shops->Shop)){


            $shops = [$shopList->Shops->Shop];


        } else {

            $shops = $shopList->Shops->Shop;

        }

        $tpl = new \imbaa\Affilipus\Admin\Utilities\adminTemplates();

        $tpl -> smarty -> assign('shopList',$shops);
        $tpl -> smarty -> display('affilinet/search.tpl');

    }

    public function import_price_page(){


        ?>

        <p>Ich rufe automatisch jede Stunde so viele Preise, wie dein Server mich lässt, über den wp-cron für dich ab. Du kannst aber auch einen eigenen Cronjob einrichten, mit dem du die Preise häufiger abrufen kannst.</p>

        <?php

        $url = IMBAF_PLUGIN_URL.'cron.php?partner=affilinet&secret='.md5(NONCE_KEY.$_SERVER['HTTP_HOST']);


        $cron_info = get_option('imbaf_cron_affilinet_refetch_prices_status');


        if($cron_info != null){

            echo "<p>Der Cronjob wurde zuletzt am ".date('d.m.Y',$cron_info['start'])." um ".date('H:i',$cron_info['start'])." ausgeführt. Ich habe {$cron_info['products_updated']} Produkt(e) aktualisiert. Das hat {$cron_info['duration']} Sekunden gedauert.</p>";

        }


        echo "<pre>";

        echo 'Deine Cronjob URL lautet: '.$url;

        echo "</pre>";


    }

    public function test_call_page(){

        echo "<h2>AFFILINET API Test</h2>";

        if(!class_exists('\SoapClient')) {
            
            echo "Leider ist der SOAP Client nicht in PHP verfügbar. Bitte wende dich an deinen Hosting Anbieter oder informiere dich hier über die installation: http://php.net/manual/de/book.soap.php";
            return;

        }

        $beginn = microtime(true);

        $this -> log("Versuche Shops von Affili.net abzurufen.");

        $shopList = $this-> client -> getShopList(false);

        if(isset($shopList->GetShopListSummary) && $shopList->GetShopListSummary->TotalRecords > 0){

            $this -> log("<strong style=\"color: green;\">API Verbindung erfolgreich.</strong>");

            ob_start();

            ?>

            <table class="widefat striped">

                <tr>

                    <td>Shop</td>
                    <td>Letztes Update</td>
                    <td>Produkte</td>

                </tr>



            <?php



            if(!array_key_exists(1,$shopList->Shops->Shop)){


                $shops = [$shopList->Shops->Shop];


            } else {

                $shops = $shopList->Shops->Shop;

            }



            foreach($shops as $shop){

                ?>

                <tr>

                    <td><?php echo $shop -> ShopTitle; ?></td>
                    <td><?php echo $shop -> LastUpdate; ?></td>
                    <td><?php echo $shop -> ProductCount; ?></td>

                </tr>

                <?php

            }

            ?>

            </table>

            <?php

            $table = ob_get_contents();
            ob_end_clean();

        } else {

            $this -> log("<strong style=\"color:red;\">API Verbindung nicht erfolgreich.</strong>");
            $table = null;

        }

        $dauer = round(microtime(true) - $beginn,2);

        $this -> log("Verarbeitungszeit {$dauer} Sek.");

        echo "<pre>";
        echo $this -> logfile;
        echo "</pre>";
        
        
        echo $table;


        return false;

        ?>


        <div class="wrap">

            <h3>Amazon API Testabruf</h3>

            <pre style="white-space:pre-wrap;"><?php echo $this -> logfile; ?></pre>



            <h4>Rohwerte</h4>
            <textarea style="width:100%;" rows="20"><?php print_r($results['raw']); ?></textarea>

        </div>


        <?php

    }

    public function debug_page(){


        $beginn = microtime(true);

        $shopList = $this-> client -> getShopList(true);

        if(isset($shopList->GetShopListSummary) && $shopList->GetShopListSummary->TotalRecords > 0){

            echo "<ul>";

            foreach($shopList->Shops->Shop as $shop){

                echo "<li>#{$shop->ShopId} $shop->ShopTitle ($shop->ProductCount)</li>";

            }

            echo "</ul>";




            $searchParams = ['shop' => $shopList->Shops->Shop[0]->ShopId,'term' => 'a'];



            $products = $this -> client -> productSearch($searchParams);


            echo "<pre>";

            print_r($products);

            echo "</pre>";




        }

        $dauer = round(microtime(true) - $beginn,2);
        echo "Verarbeitung des Skripts: $dauer Sek.";

    }

}

