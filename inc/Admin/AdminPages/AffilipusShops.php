<?php

namespace imbaa\Affilipus\Admin\AdminPages;
use imbaa\Affilipus\Entities\AffilipusShop;

class AffilipusShops {


    function __construct() {


        add_action( 'admin_menu', array( $this, 'register' ) );



    }


    public function register(){


        add_submenu_page(
            'imbaf_settings_page',
            'Shop Einstellungen',
            'Shop Einstellungen',
            'administrator',
            'imbaf_shop_settings',
            array( $this, 'render' )
        );


    }


    public function render() {

        global $wpdb;


        $affilinet = array_keys($wpdb -> get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '_imbaf_affilinet_shop_info_%'",OBJECT_K));
        $zanox = array_keys($wpdb -> get_results("SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'imbaf_zanox_shop_%'",OBJECT_K));


        foreach($affilinet as &$shop){

            $shop = str_replace('_imbaf_affilinet_shop_info_','',$shop);

        }

        foreach($zanox as &$shop){

            $shop = str_replace('imbaf_zanox_shop_','',$shop);

        }



        $shopList = [

                'affilinet' => $affilinet,
                'zanox' => $zanox

        ];



        ?>

        <div class="wrap">
            <h2>Shop Einstellungen</h2>

            <?php $this -> save(); ?>



            <p>In diesem Bereich kannst du Einstellungen für einzelne Shops deiner Affiliate Partner vornehmen.</p>

            <form method="POST">

            <?php submit_button(); ?>


            <table class="widefat" style="max-width:600px;"


                <tr>

                    <th style="width: 100px; vertical-align:top;">Logo</th>
                    <th>Shop Name</th>

                </tr>


                <?php


                foreach($shopList as $partner => $list){


                    foreach($list as &$shop){

                        $shop = new AffilipusShop($partner,$shop);

                    ?>

                        <tr>

                            <td style="text-align:center;">
                                <img src="<?php echo $shop->getLogoURL(); ?>">
                            </td>
                            <td>

                                <input type="text" name="affilipus_shops[<?php echo $shop -> getPartnerNetwork() ?>][<?php echo $shop -> getPartnerIdentifier() ?>][name]" value="<?php echo $shop -> getShopName(); ?>" class="widefat">

                            </td>

                        </tr>

                        <?php


                    }

                }



                ?>





            </table>


                <?php submit_button(); ?>

            </form>

        </div>

        <?php

    }

    public function save(){


            if(!array_key_exists('affilipus_shops',$_POST)){return;}

            $shops = $_POST['affilipus_shops'];



            foreach($shops as $partner => $shopData){


                foreach($shopData as $shopId => $shop){


                    $temp = $shop;

                    $shop = new AffilipusShop($partner,$shopId);

                    $shop -> setShopName($temp['name']);

                    $shop -> persist();

                }







            }






    }

}