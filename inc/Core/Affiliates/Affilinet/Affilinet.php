<?php

namespace imbaa\Affilipus\Core\Affiliates\Affilinet;

use imbaa\Affilipus\Core as CORE;

if (!defined('ABSPATH')) {
    die('Forbidden');
}


class Affilinet extends \imbaa\Affilipus\Core\Affiliates\affiliatePartner
{

    var $logfile = '';

    var $publisher_id = false;

    var $priceHierarchy = array(

        'offering_price',
        'lowest_new_price',
        'list_price'

    );

    public function __construct()
    {


        $this->publisher_id = get_option('imbaf_affilinet_publisher_id');

    }

    public function setup()
    {

        new \imbaa\Affilipus\Core\Affiliates\Affilinet\Admin\AdminPages();


    }


    public function getProducts($args){

        $args = shortcode_atts(
            array(
                'product_id' => 0
            ), $args);

        if($args['product_id'] != 0){


            $logon = new Logon();
            $token = $logon->getToken();

            $productIds = $args['product_id']; // enter one or more product Ids (mandatory). You can use "SearchProducts" to receive Product Ids

            // Set parameters
            $params = array(
                'CredentialToken' => $token,
                'PublisherId' => get_option('imbaf_affilinet_publisher_id'), // the Id of the requesting publisher (mandatory)
                'ProductIds' => $productIds,
                'ImageScales' => array('Image90', 'Image180'),
                'LogoScales' => array('Logo90', 'Logo150')
            );

            // Send request to Publisher Program Service
            $soapRequest = new \SoapClient(IMBAF_WSDL_PRODUCT);
            $response = $soapRequest->GetProducts($params);

            $products = array();

            if($response->ProductsSummary->TotalRecords == 0){

                echo "Es konnten keine Daten abgerufen werden.";

                die();

            }

            if($response->ProductsSummary->TotalRecords == 1){


                $items = [$response->Products->Product];

            } else {

                $items = $response->Products->Product;

            }



            foreach($items as &$item){

                $product = new CORE\Affiliates\affiliateProduct();
                

                $this -> sanitize_prices($item,$product);

                $this -> sanitize_links($item,$product);
                $this -> sanitize_identifiers($item,$product);

                $product->product_prices = $this->translatePrices($product->product_prices);
                $products[$item->ProductId] = $product;

            }



            return $products;


        } else {


            return false;

        }

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


            $logon = new Logon();
            $token = $logon->getToken();

            // Output GetPropertyList response

            // Narrow down results to specific Shops (optional)
            $shopIds = array($args['shop']); // enter one or more shop Ids. You can use "GetShopList" to receive Shop Ids
            $shopIdMode = 'Include';

            /**
             * Narrow down by Query, CategoryIds or FilterQueries (on of the three must be set)
             *
             * Query
             * Use search operators AND, OR, NOT (in capital letters) for better results
             * Use "" for an exact match
             * Use () to group expressions
             * Use wildcard * for suffix matching, e.g. 'bott*' will match bottle or bottom
             * Example: "apple ipod" ((touch OR classic) NOT nano) AND "32 GB"
             */
            $query = $args['term']; //

            /**
             * CategoryIds
             * The Ids of the categories you wish to restrict the search on. Whether the Ids are to be interpreted as shop categories
             * or as affilinet categories can be specified by using the parameter "UseAffilinetCategories".
             * You can receive shop categories using "GetCategoryList".
             */

            $categoryIds = array();
            $useAffilinetCategories = true;
            $excludeSubCategories = false;

            /**
             * Filter Queries
             * With the FilterQuery functionality, you can restrict the search results to those products that have a certain value (e.g. "Sony") in a certain data field (e.g. "Brand"). You can define up to 15 FilterQueries at the same time.
             */

            $filterQueries = array(/*array(
                    'DataField' => 'Manufacturer',
                    'FilterValue' => 'Apple'
                ),
                array(
                    'DataField' => 'Brand',
                    'FilterValue' => 'Apple'
                )*/
            );

            /**
             * Facet fields
             * With this parameter, you can specify, what facets shall be created out of the search results (e.g. "Brand", "ShopId",
             * "AffilinetCategoryPath" or "Property_Size"). See PDF documentation for a full list.
             */

            $facetFields = false;
            $facetValueLimit = 0;

            //$facetFields = array('ShopId', 'ShopName');
            //$facetValueLimit = 15;


            // Set parameters
            $params = array(
                'CredentialToken' => $token,
                'PublisherId' => get_option('imbaf_affilinet_publisher_id'), // the Id of the requesting publisher (mandatory)
                'ShopIds' => $shopIds,
                'ShopIdMode' => $shopIdMode,
                'Query' => $query,
                'CategoryIds' => $categoryIds,
                'UseAffilinetCategories' => $useAffilinetCategories,
                'ExcludeSubCategories' => $excludeSubCategories,
                'FilterQueries' => $filterQueries,
                'FacetFields' => $facetFields,
                'FacetValueLimit' => $facetValueLimit,
                'ImageScales' => array('Image90', 'Image120', 'Image180', 'OriginalImage'),
                'LogoScales' => array('Logo90', 'Logo150'),
                'WithImageOnly' => false,
                'MinimumPrice' => 0,
                'MaximumPrice' => 0,
                'PageSettings' => array(
                    'CurrentPage' => $args['page'],
                    'PageSize' => 10
                ),
                'SortBy' => 'Score',
                'SortOrder' => 'descending'
            );

            // Send request to Publisher Program Service
            $soapRequest = new \SoapClient(IMBAF_WSDL_PRODUCT);
            $response = $soapRequest->SearchProducts($params);


            // Show response

            if (isset($response->Products) && isset($response->Products->Product) && $response->ProductsSummary->TotalRecords != 0) {

                $totalResults = $response->ProductsSummary->TotalRecords;
                $totalPages = $response->ProductsSummary->TotalPages;

                if($totalResults == 1){

                    $results = array($response->Products->Product);

                } else {

                    $results = $response->Products->Product;

                }


                $products = $this->sanitizeSearchItems($results, $args);
                $responseCode = 1;


            }

        }



        return array('responseCode' => $responseCode, 'raw' => false, 'products' => $products, 'totalResults' => $totalResults, 'maxPages' => $totalPages);


    }

    public function sanitizeSearchItems($items, $args)
    {

        $products = array();


        foreach ($items as $item) {

            $product = new CORE\Affiliates\affiliateProduct();



            $product->product_name = $item->ProductName;
            $product->product_affiliate = 'affilinet';
            $product->product_affiliate_identifier = $item->ShopId;

            $product->product_type = explode('>', $item->AffilinetCategoryPath);
            $product->product_type = $product->product_type[count($product->product_type) - 1];

            if(isset($item->Brand)){

                $product->product_manufacturer = $item->Brand;

            }

            $this->sanitize_description($item, $product);
            $this->sanitize_prices($item, $product);
            $product->product_prices = $this->translatePrices($product->product_prices);

            $this->sanitize_pictures($item, $product);
            $this->sanitize_identifiers($item, $product);
            $this->sanitize_links($item, $product);

            $this->sanitize_features($item,$product);

            $product->sanitizeProduct();




            array_push($products, $product);

        }

        return $products;

    }


    public function sanitize_features(&$item,&$product){


        return false;

        if(isset($item->Properties->Property) && count($item->Properties->Property) != 0){

            if($product->product_features == null){

                $product->product_features = [];

            }

            foreach($item->Properties->Property as $feature){


                array_push();


            }

        }


    }

    public function sanitize_description(&$item, &$product)
    {

        if ($item->DescriptionShort != $item->Description) {

            $product->product_description = $item->DescriptionShort . "<br>" . '<!-- more -->' . "<br>" . $item->Description;
        } else {

            $product->product_description = $item->Description;

        }


    }

    public function sanitize_pictures(&$item, &$product)
    {

        if (!isset($product->product_pictures)) {

            $product->product_pictures = array();

        }

        if (isset($item->Images->ImageCollection)) {


            foreach ($item->Images->ImageCollection as $imageSet) {


                $image_set = [


                    'large' => false,
                    'medium' => false,
                    'small' => false,
                    'swatch' => false


                ];


                foreach ($imageSet as $image) {


                    switch ($image->ImageScale) {


                        case 'Image90':

                            $image_set['swatch'] = $image->URL;

                            break;

                        case 'Image120':

                            $image_set['small'] = $image->URL;

                            break;

                        case 'Image180':

                            $image_set['medium'] = $image->URL;

                            break;

                        case 'OriginalImage':

                            $image_set['large'] = $image->URL;

                            break;

                    }


                }


                array_push($product->product_pictures, $image_set);

            }


        }


    }

    public function sanitize_prices(&$item, &$product)
    {


        /*$product -> product_prices['list_price'] = array(
            'name' => 'list_price',
            'price' => 0,
            'currency' => 'EUR',

        );*/

        if (isset($item->PriceInformation->DisplayPrice) && isset($item->PriceInformation->Currency))

            $product->product_prices['offering_price'] = array(
                'name' => 'offering_price',
                'price' => $item->PriceInformation->DisplayPrice,
                'currency' => $item->PriceInformation->Currency,

            );

        /* $product -> product_prices['lowest_new_price'] = array(
            'name' => 'lowest_new_price',
            'price' => 0,
            'currency' => 'EUR',

        );

        $product -> product_prices['lowest_used_price'] = array(
            'name' => 'lowest_used_price',
            'price' => 0,
            'currency' => 'EUR',

        );*/



        foreach($product -> product_prices as $key => $value){

            if($value == null){

                unset($product->product_prices[$key]);

            }

        }





    }

    public function sanitize_identifiers(&$item, &$product){

        $product -> product_identification = array();


        array_push($product->product_identification, array('name' => 'product_id', 'value' => $item->ProductId, 'unique' => true));
        array_push($product->product_identification, array('name' => 'shop_id', 'value' => $item->ShopId, 'unique' => true));


        global $wpdb;

        $already_imported = $wpdb -> get_results("  SELECT COUNT(*) AS import_count
                                                FROM wp_postmeta pm 
                                                JOIN wp_postmeta pm2 ON pm2.post_id = pm.post_id
                                                JOIN wp_postmeta pm3 ON pm3.post_id = pm.post_id
                                                WHERE pm.meta_key = '_imbaf_affiliate' AND pm.meta_value = 'affilinet'
                                                AND pm2.meta_key = '_imbaf_product_id'
                                                AND pm3.meta_key = '_imbaf_shop_id'
                                                AND pm2.meta_value = {$item->ProductId} AND pm3.meta_value = {$item->ShopId};")[0];


        if($already_imported -> import_count != 0){

            $product -> product_already_imported = true;

        }



        if(isset($item->EAN)){

            array_push($product->product_identification, array('name' => 'EAN', 'value' => $item->EAN,'unique' => false));

        }


    }

    public function sanitize_links(&$item, &$product)
    {


        $product -> product_affiliate_links = array(
            array(
                'type' => 'product_page',
                'description' => 'Product Page',
                'url' => $item->Deeplink1)
        );

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


        if(get_option('imbaf_cron_affilinet_refetch_prices_status') == null){

           update_option('imbaf_cron_affilinet_refetch_prices_status','','',false);

        } else {

            $last_cron = get_option('imbaf_cron_affilinet_refetch_prices_status');

            echo "Zuletzt ausgeführt: ".date('d.m.Y H:i:s',$last_cron['start'])."\r\n\r\n";

            echo "Zuletzt aktualisierte Produkte: {$last_cron['products_updated']}\r\n\r\n";

        }


        $products = $wpdb->get_results("
            SELECT pm.post_id, pm2.meta_value AS product_id, pm3.meta_value AS shop_id 
            FROM {$wpdb->postmeta} pm 
            JOIN {$wpdb->postmeta} pm2 ON pm2.post_id = pm.post_id
            JOIN {$wpdb->postmeta} pm3 ON pm3.post_id = pm.post_id
            WHERE pm.meta_key = '_imbaf_affiliate' AND pm.meta_value = 'affilinet'
            AND pm2.meta_key = '_imbaf_product_id'
            AND pm3.meta_key = '_imbaf_shop_id';");


        if(count($products) == 0){


            echo "<br><br>Nothing to do.";
            die();


        }

        echo "<pre>";

        

        $temp_products = array();
        $product_ids = array();


        foreach($products as $product){

            $product->last_update = get_post_meta($product->post_id,'_imbaf_last_price_update',true);


            if(AFP_DEBUG == true){
                $product->last_update_minutes = 90;
                //$product->last_update_minutes = round((time()-strtotime($product->last_update))/60);

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

            }



        }


        $products = $temp_products;
        unset($temp_products);

        $product_ids = array_keys($products);


        if(count($product_ids) == 0){


            die('Nothing to do.');

        }




        $product_id_packs = array_chunk($product_ids,50);


        foreach($product_id_packs as $product_ids){

            $results = $this -> getProducts(['product_id' => $product_ids]);



            foreach($results as $product_id => $fresh_product){

                $product = $products[$product_id];

                echo "\r\n";
                echo "Aktualisiere Preise für Produkt #{$product->post_id}  ID:{$product->product_id} Titel: {$product->post->post_title} Shop: {$product->identifier}";
                echo "\r\n";
                echo "Letztes Update: {$product->last_update} (vor {$product->last_update_minutes} Minuten)";

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

                echo "\r\n\r\n";

            }

        }




        echo "</pre>";

        $cron_info['end'] = microtime(true);
        $cron_info['duration'] = round($cron_info['end']-$cron_info['start'],2);

        update_option('imbaf_cron_affilinet_refetch_prices_status',$cron_info);

    }

    public function getShopList($cached = false)
    {



        if ($cached == true && get_transient('imbaf_affilinet_shop_list')) {


           return get_transient('imbaf_affilinet_shop_list');


        }

        $logon = new Logon();
        $token = $logon->getToken();


        if($token){

            // Set page setting parameters
            $pageSettings = array(
                'CurrentPage' => 1,
                'PageSize' => 1000
            );

            // Set parameters
            $params = array(
                'CredentialToken' => $token,
                'PublisherId' => get_option('imbaf_affilinet_publisher_id'), // the Id of the requesting publisher (mandatory)
                'LogoScale' => 'Logo150',
                'PageSettings' => $pageSettings,
                'UpdatedAfter' => strtotime("-500 week"),
            );

            // Send request to Publisher Program Service
            $soapRequest = new \SoapClient(IMBAF_WSDL_PRODUCT);
            $response = $soapRequest->GetShopList($params);

            if(isset($response->Shops) && count($response->Shops->Shop) > 0){



                if(!array_key_exists(1,$response->Shops->Shop)){


                    $shops = [$response->Shops->Shop];



                } else {

                    $shops = $response->Shops->Shop;

                }



                foreach($shops as $shop){

                    update_option('_imbaf_affilinet_shop_info_'.$shop->ShopId,$shop,false);

                }

            }

            set_transient('imbaf_affilinet_shop_list', $response, 60 * 60);

            return $response;

        } else {


            return false;

        }

    }


}