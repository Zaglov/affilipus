<?php

namespace imbaa\Affilipus\Core\Affiliates\Zanox;

use imbaa\Affilipus\Core as CORE;

if (!defined('ABSPATH')) {
    die('Forbidden');
}


class Zanox extends \imbaa\Affilipus\Core\Affiliates\affiliatePartner
{

    var $logfile = '';

    var $connect_id = false;
    var $secret_key = false;

    var $priceHierarchy = array(

        'offering_price',
        'lowest_new_price',
        'list_price'

    );

    public function __construct()
    {


        $this->connect_id = get_option('imbaf_zanox_connect_id');
        $this->secret_key = get_option('imbaf_zanox_secret_key');

    }

    public function setup()
    {

        new \imbaa\Affilipus\Core\Affiliates\Zanox\Admin\AdminPages();

    }

    public function productSearch($args)
    {


        $args = shortcode_atts(
            array(
                'page' => 1,
                'term' => '',
                'shop' => false,
            ), $args);




        $response = false;
        $responseCode = 0;
        $products = array();
        $totalResults = array();
        $totalPages = array();

        if ($args['shop'] != false) {
            

            require_once(IMBAF_LIBRARY.'/zanoxapi/ApiClient.php');

            $connectId = $this -> connect_id;
            $secretKey = $this -> secret_key;

            $api = \ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);

            $api->setConnectId($connectId);
            $api->setSecretKey($secretKey);



            $query      = $args['term'];
            $searchType = 'phrase';
            $programs   = array();
            $region     = NULL;
            $categoryId = NULL;
            $programId  = array();
            $hasImages  = true;
            $minPrice   = 0;
            $maxPrice   = NULL;
            $adspaceId  = $args['shop'];
            $page       = 0;
            $items      = 10;

            $json = $api->searchProducts($query, $searchType, $region,
                $categoryId, $programId, $hasImages, $minPrice,
                $maxPrice, $adspaceId, $page, $items);

            $result = json_decode($json);


            if(!isset($result->items) || $result->items == 0){

                $responseCode = 0;

            }

            // Show response

            if (isset($result->productItems) && isset($result->productItems->productItem)) {

                $totalResults = $result->total;
                $totalPages = ceil($result->total/10);

                $results = $result->productItems->productItem;

                $products = $this->sanitizeSearchItems($results, $args);
                $responseCode = 1;

            } else {


                $responseCode = 0;

            }

        }

        return array('responseCode' => $responseCode, 'raw' => false, 'products' => $products, 'totalResults' => $totalResults, 'maxPages' => $totalPages);


    }

    public function getShopInfo($program_id = null, $cached = false){


        if($program_id != null){


            if(!get_option('imbaf_zanox_shop_'.$program_id) || $cached = false){

                $connectId = $this -> connect_id;
                $secretKey = $this -> secret_key;

                $api = \ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);

                $api->setConnectId($connectId);
                $api->setSecretKey($secretKey);

                $program = $api -> getProgram ( $program_id );

                $program = json_decode($program);

                if(!$program) {return false;}

                $shop = [

                    'id' => $program_id,
                    'name' => $program->programItem[0] -> name,
                    'logo' => $program->programItem[0] -> image

                ];

                add_option('imbaf_zanox_shop_'.$program_id,$shop,'',false);


                return $shop;


            } else {


                return get_option('imbaf_zanox_shop_'.$program_id);

            }




        }


        return false;


    }

    public function sanitizeSearchItems($items, $args)
    {


        $args = shortcode_atts(
            array(
                'finalize' => true
            ), $args);

        $products = array();


        foreach ($items as $item) {

            $this -> getShopInfo(get_object_vars($item->program)['@id']);

            $item_array = get_object_vars ($item);

            $product = new CORE\Affiliates\affiliateProduct();

            $product->product_name = $item->name;
            $product->product_affiliate = 'zanox';
            $product->product_affiliate_identifier = get_object_vars($item->program)['@id'];
            $product->product_type =$item->merchantCategory;


            if(isset($item->manufacturer)){

                $product->product_manufacturer = $item->manufacturer;

            }

            $this->sanitize_description($item, $product);

            $this->sanitize_prices($item, $product);
            $product->product_prices = $this->translatePrices($product->product_prices);
            $this->sanitize_pictures($item, $product);

            $this->sanitize_identifiers($item, $product);
            $this->sanitize_links($item, $product);

            if($args['finalize']){

            $product->sanitizeProduct();

            }

            array_push($products, $product);

        }


        return $products;

    }

    public function sanitize_features(&$item,&$product){


        return false;


    }

    public function sanitize_description(&$item, &$product)
    {


        $product -> product_description = $item -> description;




    }

    public function sanitize_pictures(&$item, &$product)
    {


        if(isset($item->image->large)){

            $product -> product_pictures = [];

            $image_set = [


                'large' => $item->image->large,
                'medium' => false,
                'small' => false,
                'swatch' => false


            ];

            array_push($product->product_pictures, $image_set);

        }

    }

    public function sanitize_prices(&$item, &$product)
    {



        if (isset($item->price) && isset($item->currency)){

            $product->product_prices['offering_price'] = array(
                'name' => 'offering_price',
                'price' => $item->price,
                'currency' => $item->currency,

            );

            foreach($product -> product_prices as $key => $value){

                if($value == null){

                    unset($product->product_prices[$key]);

                }

            }

        }







    }

    public function sanitize_identifiers(&$item, &$product){

        global $wpdb;
        

        $product -> product_identification = array();

        array_push($product->product_identification, array('name' => 'product_id', 'value' => get_object_vars($item)['@id'], 'unique' => true));
        array_push($product->product_identification, array('name' => 'shop_id', 'value' => get_object_vars($item->program)['@id'], 'unique' => true));



        $imported_query = "SELECT IFNULL(COUNT(*),0) AS import_count
                                                FROM wp_postmeta pm 
                                                JOIN wp_postmeta pm2 ON pm2.post_id = pm.post_id
                                                JOIN wp_postmeta pm3 ON pm3.post_id = pm.post_id
                                                WHERE pm.meta_key = '_imbaf_affiliate' AND pm.meta_value = 'zanox'
                                                AND pm2.meta_key = '_imbaf_product_id'
                                                AND pm3.meta_key = '_imbaf_shop_id'
                                                AND pm2.meta_value = '{$item->merchantProductId}' AND pm3.meta_value = ".get_object_vars($item->program)['@id'].";";

        $already_imported = $wpdb -> get_results($imported_query)[0];


        if($already_imported -> import_count != 0){

            $product -> product_already_imported = true;

        }






        if(isset($item->ean)){

            array_push($product->product_identification, array('name' => 'EAN', 'value' => $item->ean,'unique' => false));

        }


    }

    public function sanitize_links(&$item, &$product)
    {



        $product -> product_affiliate_links = array(
            array(
                'type' => 'product_page',
                'description' => 'Product Page PPC',
                'url' => $item->trackingLinks->trackingLink[0]->ppc),
            array(
                'type' => 'product_page_ppv',
                'description' => 'Product Page PPV',
                'url' => $item->trackingLinks->trackingLink[0]->ppv),

        );

    }

    public function getClient(){

        require_once(IMBAF_LIBRARY.'/zanoxapi/ApiClient.php');


        $connectId = $this -> connect_id;
        $secretKey = $this -> secret_key;

        $api = \ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);

        $api->setConnectId($connectId);
        $api->setSecretKey($secretKey);
        
        return $api;

    }

    public function refetchPrices()
    {
        
        global $wpdb;

        $api = new CORE\API\affilipusAPI();
        if(!$api->allowAction()){die();}

        $cron_info = array(

            'start' => microtime(true),
            'end' => null,
            'duration' => null,
            'products_updated' => 0

        );


        if(get_option('imbaf_cron_zanox_refetch_prices_status') == null){

           update_option('imbaf_cron_zanox_refetch_prices_status','','',false);

        } else {

            $last_cron = get_option('imbaf_cron_zanox_refetch_prices_status');

            echo "Zuletzt ausgeführt: ".date('d.m.Y H:i:s',$last_cron['start'])."\r\n\r\n";

            echo "Zuletzt aktualisierte Produkte: {$last_cron['products_updated']}\r\n\r\n";

        }


        $product_query = "
            SELECT pm.post_id, pm2.meta_value AS product_id, pm3.meta_value AS shop_id 
            FROM {$wpdb->postmeta} pm 
            JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = pm.post_id
            JOIN {$wpdb->postmeta} pm3 ON pm3.post_id = pm.post_id
            WHERE pm.meta_key = '_imbaf_affiliate' AND pm.meta_value = 'zanox'
            AND pm2.meta_key = '_imbaf_product_id'
            AND pm3.meta_key = '_imbaf_shop_id';";



        $products = $wpdb->get_results($product_query);

        if(count($products) == 0){

            echo "<br><br>Nothing to do!";
            die();

        }

        echo "<pre>";


        $temp_products = array();
        $product_ids = array();
        $shop_ids = array();

        $api = $this -> getClient();

        if(count($products) > 0){

            foreach($products as $product){

                $product->last_update = get_post_meta($product->post_id,'_imbaf_last_price_update',true);


                if(AFP_DEBUG == true){
                    $product->last_update_minutes = 90;

                } else {
                    $product->last_update_minutes = round((time()-strtotime($product->last_update))/60);
                }


                if($product->last_update_minutes >= 60) {

                    $product->post = get_post($product->post_id);
                    $product->current_prices = get_post_meta($product->post_id, '_imbaf_price', true);
                    $product->current_links = get_post_meta($product->post_id, '_imbaf_affiliate_links', true);
                    $product->selected_price = get_post_meta($product->post_id, '_imbaf_selected_price', true);
                    $product->identifier = get_post_meta($product->post_id, '_imbaf_affiliate_identifier', true);

                    $temp_products[$product->product_id] = $product;


                    array_push($shop_ids,$product->identifier);



                }

            }

        }


        $products = $temp_products;
        unset($temp_products);

        $product_ids = array_keys($products);


        if(count($product_ids) == 0){
            
            die('Nothing to do.');

        }

        echo "Aktualisiere Shopinformationen";

        foreach($shop_ids as $shop_id){
            $this -> getShopInfo($shop_id,false);
        }

        echo "\r\n\r\n";

        foreach($products as $product){

            echo "\r\n";
            echo "Aktualisiere Preise für Produkt #{$product->post_id}  ID:{$product->product_id} Titel: {$product->post->post_title} Shop: {$product->identifier}";
            echo "\r\n";
            echo "Letztes Update: {$product->last_update} (vor {$product->last_update_minutes} Minuten)";


            $fresh_product = json_decode($api -> getProduct($product->product_id));


            if(!isset($fresh_product -> productItem)){

                echo "\r\nProdukt nicht gefunden.";

            } else {

                $fresh_product = $fresh_product -> productItem[0];
                $fresh_product = $this->sanitizeSearchItems([$fresh_product], ['finalize' => false])[0];

                if(array_key_exists($product->selected_price,$fresh_product->product_prices)){

                    echo "\r\nGewählte Preisart '{$product->selected_price}' verfügbar.";

                } else {

                    echo "\r\nGewählte Preisart '{$product->selected_price}' nicht verfügbar. Ersetze durch: ";
                    foreach($this->priceHierarchy as $price_type){

                        if(array_key_exists($price_type,$fresh_product->product_prices)){

                            update_post_meta($product->post_id, '_imbaf_selected_price', $price_type, $product->selected_price);

                            echo "'{$price_type}'.";

                            break;

                        }

                    }

                }

                update_post_meta($product->post_id, '_imbaf_affiliate_links', $fresh_product->product_affiliate_links, $product->current_links);
                update_post_meta($product->post_id, '_imbaf_price', array_values($fresh_product->product_prices), $product->current_prices);
                update_post_meta($product->post_id, '_imbaf_last_price_update', date('Y-m-d H:i:s',time()), $product->last_update,true);

                wp_cache_delete( 'imbaf_product_'.$product->post_id , 'imbaf_products');

                $cron_info['products_updated']++;

            }







            echo "\r\n\r\n";

        }

        $cron_info['end'] = microtime(true);
        $cron_info['duration'] = round($cron_info['end']-$cron_info['start'],2);

        echo "\r\nFertig";

        echo "</pre>";

        update_option('imbaf_cron_zanox_refetch_prices_status',$cron_info);

    }

    public function getSpacesList($cached = false){

        
        $cached = false;
        
        if ($cached == true && get_transient('imbaf_zanox_spaces_list')) {

            return get_transient('imbaf_zanox_spaces_list');

        }

        require_once(IMBAF_LIBRARY.'/zanoxapi/ApiClient.php');

        $api = \ApiClient::factory(PROTOCOL_JSON, VERSION_DEFAULT);

        $connectId = $this ->connect_id;
        $secretKey = $this->secret_key;

        $api->setConnectId($connectId);
        $api->setSecretKey($secretKey);

        //$json = $api->searchPrograms(NULL,NULL,null,true,'DE',NULL);
        $json = $api-> getAdspaces(0,200);

        $data = json_decode($json,TRUE);
        
   

        $spaces = [];

        if($data && $data['items'] > 0){


            foreach($data['adspaceItems']['adspaceItem'] as $space){
                

                $spaceinfo = [

                    'id' => $space['@id'],
                    'name' => $space['name']

                ];
                


                $spaces[$space['@id']] = $spaceinfo;

            }

            set_transient('imbaf_zanox_spaces_list', $spaces, 60 * 60);



            return $spaces;

        } else {

            delete_transient('imbaf_zanox_spaces_list');

        }



        return false;




    }

}