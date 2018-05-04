<?php


/** HTML-Shortcodes */

namespace imbaa\Affilipus\Core\Output;
use imbaa\Affilipus\Core as CORE;


class imbafShortcodesHTML extends imbafShortcodesTEXT {


    /* Product Picture */

    function affilipus_product_picture($args){


        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'description_long' => 'Importiertes Produkt, für das die Reviews angezeigt werden sollen.',
                'type' => 'product',

            ),
            'class' => array(
                'value' =>  'size-full',
                'description' => 'Standard CSS-Klassen',
                'description_long' => 'Standard CSS-Klassen',
                'wp_option' => 'imbaf_shortcode_product_picture_classes'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();


        if(isset($this -> product['product_picture'])){


            return "<img src='{$this -> product['product_picture']}' class='affilipus-product-picture {$args['class']}'>";

        }

    }

    /* Amazon Reviews */

    function amazon_reviews($args){


        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'description_long' => 'Importiertes Produkt, für das die Reviews angezeigt werden sollen.',
                'type' => 'product',

            ),


            'iframe' => array(
                'value' =>  'yes',
                'description' => 'Darstellung',
                'description_long' => 'Sollen die Inhalte direkt in die Seite eingebunden werden, oder per iFrame ausgegeben werden? <strong>iFrame empfohlen!</strong>.',
                'type' => 'select',
                'options' => array(array('option_text' => 'Alf iframe (empfohlen)', 'option_value' => 'yes'), array('option_text' => 'Innerhalb der Seite', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_amazon_reviews_use_iframe',
                'internal' => true
            ),
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];


        }

        $this -> prepare_data();

        if(isset($this -> product['_imbaf_reviews_iframe_link']) && $this -> product['_imbaf_reviews_iframe_link'] != null){

            if($args['iframe']){

                $id = 'imbaf_reviews_product_'.$this->product['id'];

                wp_register_script('imbaf-amazon-reviews',IMBAF_PLUGIN_URL.'js/amazon_reviews.js',['jquery'],true);

                wp_localize_script( 'imbaf-amazon-reviews', 'settings', [
                    'container_id' => $id,
                    'product_id' => $this->product['id'],
                    'ajax_url' => admin_url( 'admin-ajax.php' )
                ] );

                wp_enqueue_script('imbaf-amazon-reviews');


                return "<div id='{$id}'></div>";

            } else {


                $reviews = file_get_contents($this -> product['_imbaf_reviews_iframe_link']);


                $reviews = str_replace('target="_top"','target="_blank" rel="nofollow"',$reviews);


                $reviews = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $reviews);

                return $reviews;

            }


        }




    }

    /*Grid*/

    function grid($args){

        $default_template = 'shortcode_grid';

        $default_args = array(
            'products' => array('value' => null, 'description' => 'Affilipus Produkt IDs der darzustellenden Produkte, Kommagetrennt'),
            'asins' => array('value' => null, 'description' => 'ASINS der darzustellenden Produkte, durch Komma getrennt'),
            //'cols' => array('value' => 3, 'description' => 'Spaltenzahl'),
            'title_to_review' => array(
                'value' =>  'yes',
                'description' => 'Titel Verlinkung',
                'description_long' => 'Soll der Titel des Produktes mit der Affilipus Produktseite oder dem Shop verlinkt werden.',
                'type' => 'select',
                'options' => array(array('option_text' => 'Mit Produktseite', 'option_value' => 'yes'), array('option_text' => 'Mit Shop', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_grid_title_to_review'
            ),
            'display_buy_button' => array(
                'value' => 'yes',
                'description' => 'Kaufbutton',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_grid_display_buy_button'),
            'buy_button_text' => array(
                'value' =>  'Jetzt kaufen',
                'description' => 'Kaufbutton Beschriftung',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_grid_buy_button_text'),
            'buy_button_icon' => array(
                'value' => 'fa-shopping-cart',
                'description' => 'Kaufbutton Icon',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_grid_buy_button_icon'
            ),
            'display_review_button' => array(
                'value' => 'no',
                'description' => 'Review Button',
                'description_long' => '',
                'type' => 'select','options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_grid_display_review_button'),
            'review_button_text' => array(
                'value' =>  'Zur Review',
                'description' => 'Review Button Beschriftung',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_grid_review_button_text'
            ),
            'review_button_icon' => array(
                'value' => null,
                'description' => 'Review Button Icon',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_grid_review_button_icon'
            ),

            'lang' => array(
                'value'            => 'de',
                'description'      => 'Amazon Shop',
                'description_long' => 'Amazon Shop aus dem die Daten bezogen werden sollen.',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Deutschland', 'option_value' => 'de' ),
                    array( 'option_text' => 'Englang', 'option_value' => 'co.uk' ),
                    array( 'option_text' => 'Frankreich', 'option_value' => 'fr' ),
                    array( 'option_text' => 'Spanien', 'option_value' => 'es' ),
                    array( 'option_text' => 'Polen', 'option_value' => 'pl' ),
                ),
                'wp_option' => 'imbaf_shortcode_grid_lang'
            ),

            'link_product_picture' => array(
                'value' => 'no',
                'description' => 'Produktbild verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_grid_link_product_picture'
            ),

            'link_prime_logo' => array(
                'value' => 'no',
                'description' => 'Prime Logo verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_grid_link_prime_logo'
            ),


            'template' => array(
                'value' => $default_template,
                'description' => 'Template zum Verwenden des Shortcodes'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard Template für den Shortcode',
                'internal' => true
            )

        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);

        $args = $this -> handle_true_false($args);

        if(get_option('imbaf_enable_product_pages') != 1){

            $args['title_to_review'] = false;
            $args['display_review_button'] = false;

        }

        $args['products'] = explode(',',$args['products']);
        $args['asins'] = explode(',',$args['asins']);

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-formats');
        wp_enqueue_style('imbaf-shadows');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('open-sans');

        $data = [];

        $data['products'] = [];


        if(count($args['asins'] != 0) && $args['asins'][0] != ''){

            $partner = new CORE\Affiliates\Amazon\partnerAmazon();

            $products = $partner -> temporaryImportProducts($args['asins'],$args['lang'],'grid-'.md5(serialize($args)));

            if(!$products) {

                if(current_user_can('administrator')){

                    return "<div style='padding: 50px; clear:both;'>Fehler beim Abruf der Produktdaten mit den ASINS ".implode(',',$args['asins']).".</div>";

                } else {

                    return '';

                }

            }


        } else {

            $products = $args['products'];

        }

        $product = new CORE\Affiliates\affiliateProduct();


        foreach($products as $p){

            array_push($data['products'],$product->loadProductById($p));

        }

        $this -> sideload_stylesheet('shortcode_grid','imbaf-'.$args['template']);
        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);

        $output = '';

        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        return $output;


    }


    /* Produktbox */

    function product_box($args){

        $affiliateProduct = new \imbaa\Affilipus\Core\Affiliates\affiliateProduct();

        $default_template = 'shortcode_product_box';

        $default_args = array(
            'product' => array('value' => null, 'description' => 'Produkt-ID', 'type' => 'product'),
            'asin' => array('value' => null, 'description' => 'ASIN eines Produktes'),
            'title_to_review' => array(
                'value' =>  'yes',
                'description' => 'Titel Verlinkung',
                'description_long' => 'Soll der Titel des Produktes mit der Affilipus Produktseite oder dem Shop verlinkt werden.',
                'type' => 'select',
                'options' => array(array('option_text' => 'Mit Produktseite', 'option_value' => 'yes'), array('option_text' => 'Mit Shop', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_product_box_title_to_review'
            ),
            'display_buy_button' => array(
                'value' => 'yes',
                'description' => 'Kaufbutton',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_display_buy_button'),
            'buy_button_text' => array(
                'value' =>  'Jetzt kaufen',
                'description' => 'Kaufbutton Beschriftung',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_product_box_buy_button_text'),

            'buy_button_icon' => array(
                'value' => 'fa-shopping-cart',
                'description' => 'Kaufbutton Icon',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_product_box_buy_button_icon'
            ),

            'display_review_button' => array(
                'value' => 'no',
                'description' => 'Review Button',
                'description_long' => '',
                'type' => 'select','options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_product_box_display_review_button'),

            'review_button_text' => array(
                'value' =>  'Zur Review',
                'description' => 'Review Button Beschriftung',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_product_box_review_button_text'
            ),

            'review_button_icon' => array(
                'value' => null,
                'description' => 'Review Button Icon',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_product_box_review_button_icon'
            ),

            'lang' => array(
                'value'            => 'de',
                'description'      => 'Amazon Shop',
                'description_long' => 'Amazon Shop aus dem die Daten bezogen werden sollen.',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Deutschland', 'option_value' => 'de' ),
                    array( 'option_text' => 'Englang', 'option_value' => 'co.uk' ),
                    array( 'option_text' => 'Frankreich', 'option_value' => 'fr' ),
                    array( 'option_text' => 'Spanien', 'option_value' => 'es' ),
                    array( 'option_text' => 'Polen', 'option_value' => 'pl' ),
                ),
                'wp_option' => 'imbaf_shortcode_product_box_lang'
            ),

            'data_only' => array(
                'value' => false,
                'description' => 'Nur Daten abfragen. Interner Parameter.',
                'description_long' => '',
                'internal' => true
            ),

            'display_product_rating' => array(
                'value' => 'yes',
                'description' => 'Produktbewertung',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_display_product_rating'
            ),

            'display_product_picture' => array(
                'value' => 'yes',
                'description' => 'Produktbild',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_display_product_picture'),

            'display_product_features' => array(
                'value' => 'yes',
                'description' => 'Produktfeatures',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_display_product_features'
            ),

            'display_product_description' => array(
                'value' => 'yes',
                'description' => 'Produktbeschreibung',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_display_product_description'
            ),

            'link_product_picture' => array(
                'value' => 'no',
                'description' => 'Produktbild verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_product_box_link_product_picture'
            ),

            'link_prime_logo' => array(
                'value' => 'no',
                'description' => 'Prime Logo verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_product_box_link_prime_logo'
            ),

            'show_all_prices' => array(
                'value' => 'no',
                'description' => 'Preisvergleich automatisch ausklappen',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'ja', 'option_value' => 'yes')
                ),
                'wp_option' => 'imbaf_shortcode_product_box_show_all_prices'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template zum Verwenden des Shortcodes'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard Template für den Shortcode',
                'internal' => true
            )

        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);

        $args = $this -> handle_true_false($args);

        if($args['product'] == null && $args['asin'] == null){

            $args['product'] = $this -> get_product_id();

        }

        if(get_option('imbaf_enable_product_pages') != 1){

            $args['title_to_review'] = false;
            $args['display_review_button'] = false;

        }

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-formats');
        wp_enqueue_style('imbaf-shadows');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('open-sans');

        wp_enqueue_script('imbaf-preisvergleich',IMBAF_PLUGIN_URL.'js/preisvergleich.js',['jquery']);

        $this -> sideload_stylesheet('shortcode_product_box','imbaf-'.$args['template']);
        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);

        $output = '';

        $args['uniqid'] = uniqid();

        if($args['asin'] != null){

            $args['term'] = $args['asin'];
            $args['limit'] = 1;
            $args['group_prefix'] = 'product-box-by-asin-'.$args['asin'].'-';

            $partner = new CORE\Affiliates\Amazon\partnerAmazon();
            $products = $partner -> topsellerSearch($args);

            if(count($products) == 0){

                return '<p>Leider konnte kein passendes Produkt gefunden werden.</p>';

            }

            $product = $products[0];

            $args['product'] = $product;

            unset($args['asin']);

            $output = $this -> product_box($args);

        }

        else if ($args['product'] != null){

            $this -> post_id = $args['product'];
            $this -> prepare_data();


            if(!$this->product){ return $this->product_missing();}


            if(isset($this->product['product_picture'])){

                $post_thumbnail = '<img class="imbaf_product_thumbnail" src="'.$this->product['product_picture'].'">';

            } else {

                $post_thumbnail = null;

            }



            $data = array('post_thumbnail' => $post_thumbnail,'product' => $affiliateProduct->preisvergleich($this->product));

            if($args['data_only'] == true){

                return $data;

            }

            $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        }

        return $output;

    }

    /* Topseller Liste */

    function affilipus_topseller($args){



        $default_template = 'shortcode_topseller_list';

        $default_args = array(
            'term' => array('value' => 'Kekse', 'description' => 'Suchbegriff'),
            'limit' => array(
                'value' => 10,
                'description' => 'Limit',
                'description_long' => 'Limitiert die Anzahl der Ergebnisse',
                'wp_option' => 'imbaf_shortcode__affilipus_topseller_limit'
            ),
            'display_buy_button' => array(
                'value' => 'yes',
                'description' => 'Kaufbutton',
                'type' => 'select',
                'options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_display_buy_button'
            ),
            'buy_button_text' => array(
                'value' =>  'Jetzt kaufen',
                'description' => 'Kaufbutton Beschriftung',
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_buy_button_text'
            ),
            'buy_button_icon' => array(
                'value' => 'fa-shopping-cart',
                'description' => 'Font Awesome Icon neben Beschriftung.',
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_buy_button_icon'
            ),
            'display_review_button' => array(
                'value' => 'no',
                'description' => 'Reviewbutton',
                'type' => 'select','options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_display_review_button'),
            'review_button_text' => array(
                'value' =>  'Zur Review',
                'description' => 'Reviewbutton Beschriftung',
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_review_button_text'),
            'review_button_icon' => array(
                'value' => null,
                'description' => 'Font Awesome Icon neben Beschriftung',
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_review_button_icon'
            ),
            'title_to_review' => array(
                'value' =>  'yes',
                'description' => 'Titel Verlinkung',
                'description_long' => 'Soll der Titel des Produktes mit der Affilipus Produktseite oder dem Shop verlinkt werden.',
                'type' => 'select',
                'options' =>  array(array('option_text' => 'Mit Produktseite', 'option_value' => 'yes'), array('option_text' => 'Mit Shop', 'option_value' => 'no'))),
            'lang' => array(
                'value' => 'de',
                'description'      => 'Amazon Shop',
                'description_long' => 'Amazon Shop aus dem die Daten bezogen werden sollen.',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Deutschland', 'option_value' => 'de' ),
                    array( 'option_text' => 'Englang', 'option_value' => 'co.uk' ),
                    array( 'option_text' => 'Frankreich', 'option_value' => 'fr' ),
                    array( 'option_text' => 'Spanien', 'option_value' => 'es' ),
                    array( 'option_text' => 'Polen', 'option_value' => 'pl' ),
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_lang'),
            'display_product_rating' => array(
                'value' => 'yes',
                'description' => 'Produktbewertung anzeigen (true/false)',
                'type' => 'select',
                'options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_display_product_rating'),
            'display_product_features' => array(
                'value' => 'yes',
                'description' => 'Produktfeatures',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_display_product_features'
            ),
            'display_bestseller_label' => array(
                'value' => 'yes',
                'description' => 'Topseller Label',
                'description_long' => 'Topseller Label anzeigen oder verstecken',
                'type' => 'select',
                'options' => array(array('option_text' => 'anzeigen', 'option_value' => 'yes'), array('option_text' => 'nicht anzeigen', 'option_value' => 'no')),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_display_bestseller_label'),
            'exclude' => array(
                'value' => null,
                'description' => 'ASINS durch Komma getrennt, die nicht in der Liste auftauchen sollen'),
            'asins' => array('value'=>null,'description' => 'Durch Komma getrennte ASINS für individuelle Topseller Listen'),
            'products' => array('value'=>null,'description' => 'Durch Komma getrennte Produkt-IDs für individuelle Topseller Listen'),
            'link_product_picture' => array(
                'value' => 'no',
                'description' => 'Produktbild verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_link_product_picture'
            ),

            'link_prime_logo' => array(
                'value' => 'no',
                'description' => 'Prime Logo verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_link_prime_logo'
            ),


            'show_all_prices' => array(
                'value' => 'no',
                'description' => 'Preisvergleich automatisch ausklappen',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'ja', 'option_value' => 'yes')
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_topseller_show_all_prices'
            ),

            'template' => array('value' => $default_template, 'description' => 'Template, das verwendet werden soll.'),
            'default_template' => array('value' => $default_template, 'description' => 'Standard Template des Shortcodes', 'internal' => true)
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if(get_option('imbaf_enable_product_pages') != 1){

            $args['title_to_review'] = false;
            $args['display_review_button'] = false;

        }


        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet('shortcode_topseller_list','imbaf-'.$default_template);

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-formats');
        wp_enqueue_style('imbaf-shadows');
        wp_enqueue_style('font-awesome');
        wp_enqueue_style('open-sans');

        $partner = new CORE\Affiliates\Amazon\partnerAmazon();

        global $wpdb;

        if($args['asins'] != null){

            $products = $partner -> temporaryImportProducts(explode(',',$args['asins']),$args['lang'],'toplist-'.md5(serialize($args['asins'])));


            if(!$products) {

                if(current_user_can('administrator')){

                    return "<div style='padding: 50px; clear:both;'>Fehler beim Abruf der Produktdaten mit den ASINS ".implode(',',$args['asins']).".</div>";

                } else {

                    return '';

                }

            }

        }

        else if ($args['products'] != null){

            $args['term'] = null;
            $args['asins'] = null;
            $args['products'] = preg_replace('/\s+/', '', $args['products']);
            $args['products'] = explode(',',$args['products']);

            $products = $args['products'];

        }

        else if ($args['term'] != null) {

            $products = $partner -> topsellerSearch($args);

        }

        else {

            $args['term'] = 'PS4';
            $products = $partner -> topsellerSearch($args);

        }

        $data = array();

        if($products && count($products) > 0){


            foreach($products as $key => $product){

                $args['product'] = $product;
                $args['data_only'] = true;

                if(isset($args['product']) && $args['product'] != null){

                    array_push($data,$this -> product_box($args));

                }

            }

        }

        else {

            return $this -> product_missing();

        }

        if(count($data) == 0){

            return $this -> product_missing();

        }

        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        return $output;

    }

    /* Einfacher Button */

    function affilipus_button($args){

        $default_template = 'shortcode_button';

        wp_enqueue_style('imbaf-buttons');

        $default_args = array(
            'target' => array(
                'value' => '_blank',
                'description' => 'Linkziel',
                'description_long' => '',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Neues Fenster', 'option_value' => '_blank' ),
                    array( 'option_text' => 'Selbes Fenster', 'option_value' => '_self' )
                ),
                'wp_option' => 'imbaf_shortcode_button_target'
            ),

            'button_text' => array(
                'value' => 'Jetzt kaufen',
                'description' => 'Button Beschriftung',
                'description_long' => '',
            ),

            'link' => array(
                'value' => 'https://',
                'description' => 'Linkziel',
                'description_long' => ''
            ),

            'fa_icon' => array(
                'value' => false,
                'description' => 'Icon',
                'description_long' => 'Font Awesome Icon neben Beschriftung. false für "kein Icon".',
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);


        $output = $this -> sideload_template($args['template'],$default_template,$args,array('product' => $this->product));

        return $output;

    }


    /* Jetzt kaufen Button */

    function buy_button($args){

        $default_template = 'shortcode_buy_button';

        wp_enqueue_style('imbaf-buttons');

        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'description_long' => 'Produkt-ID des darzustellenden Produktes.',
                'type' => 'product'
            ),

            'target' => array(
                'value' => '_blank',
                'description' => 'Linkziel',
                'description_long' => '',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Neues Fenster', 'option_value' => '_blank' ),
                    array( 'option_text' => 'Selbes Fenster', 'option_value' => '_self' )
                ),
                'wp_option' => 'imbaf_shortcode_buy_button_target'
            ),

            'button_text' => array(
                'value' => 'Jetzt kaufen',
                'description' => 'Button Beschriftung',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_buy_button_button_text'
            ),

            'fa_icon' => array(
                'value' => 'fa-shopping-cart',
                'description' => 'Icon',
                'description_long' => 'Font Awesome Icon neben Beschriftung. False für "kein Icon".',
                'wp_option' => 'imbaf_shortcodes_buy_button_fa_icon'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);




        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();

        if(!$this->product){return $this->product_missing();}

        $output = $this -> sideload_template($args['template'],$default_template,$args,array('product' => $this->product));

        return $output;

    }

    /* Add2Cart Button */

    function add2cart_button($args){


        $default_template = 'shortcode_add2cart_button';

        wp_enqueue_style('imbaf-buttons');

        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt-ID des darzustellenden Produktes',
                'type' => 'product'
            ),

            'target' => array(
                'value' => '_blank',
                'description' => 'Linkziel',
                'description_long' => '',
                'type'             => 'select',
                'options'          => array(
                    array( 'option_text' => 'Neues Fenster', 'option_value' => '_blank' ),
                    array( 'option_text' => 'Selbes Fenster', 'option_value' => '_self' )
                ),
                'wp_option' => 'imbaf_shortcode_add2cart_button_target'
            ),

            'button_text' => array(
                'value' => 'In Warenkorb legen',
                'description' => 'Button Beschriftung',
                'wp_option' => 'imbaf_shortcode_add2cart_button_button_text'
            ),

            'fa_icon' => array(
                'value' => 'fa-cart-arrow-down',
                'description' => 'Icon',
                'description_long' => 'Font Awesome Icon neben Beschriftung. False für "kein Icon".',
                'wp_option' => 'imbaf_shortcode_add2cart_button_fa_icon'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }


        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);



        $this -> prepare_data();

        $output = $this -> sideload_template($args['template'],$default_template,$args,array('product' => $this->product));

        return $output;


    }

    /* Preisliste */

    function price_list($args){


        $default_template = 'shortcode_price_list';

        $default_args = array(

            'title' => array(
                'value' => null,
                'description' => 'Überschrift',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_price_list_title'
            ),

            'buy_button_text' => array(
                'value' => 'Zum Shop',
                'description' => 'Buy Button Text',
                'description_long' => '',
                'wp_option' => 'imbaf_shortcode_price_list_buy_button_text'
            ),

            'buy_button_icon' => array(
                'value' => 'fa-shopping-cart',
                'description' => 'Buy Button Icon',
                'description_long' => 'Icon für den Buy Button',
                'wp_option' => 'imbaf_shortcode_price_list_buy_button_icon'
            ),

            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll.',
                'description_long' => '',
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard System-Template',
                'description_long' => '',
                'internal' => true
            )

        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet('shortcode_buy_button',$default_template);

        wp_enqueue_style('imbaf-shadows');

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-tables');
        wp_enqueue_style('imbaf-formats');


        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);



        if($args['product'] != null){

            $this -> post_id = $args['product'];

        } else {

            $this -> post_id = $this -> get_product_id();

        }

        $this -> prepare_data();

        if(!$this->product){
            return $this->product_missing();
        }

        $products = array($this -> product);
        $products = array_merge($products,$this->product['children']);

        usort($products,function($a,$b){return $a['_imbaf_display_price']['price'] - $b['_imbaf_display_price']['price'];});

        $output = '';

        $data = array('products' => $products);


        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        return $output;

    }

    /* Affilipus Feature Liste */

    function feature_list($args){


        $default_args = array(
            'title' => array(
                'value' => null,
                'description' => 'Titel',
                'description_long' => 'Titel, der vor der Feature-Liste ausgegeben werden soll (optional)',
                'wp_option' => 'imbaf_shortcode_feature_list_title'),
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();


        if(!$this->product){

            return $this -> product_missing();

        }


        $output = '';


        $data = array('product' => $this->product);

        $output .= $this -> sideload_template('shortcode_feature_list','shortcode_feature_list',$args,$data);

        return $output;



    }

    /* Review Box */

    function review_box($args){

        $default_template = 'shortcode_review_box';

        $default_args = array(

            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'description_long' => 'Produkt-ID des Produktes, dessen Review angezeigt werden soll. Optional, wenn es auf einer Produktseite eingesetzt wird.',
                'type' => 'product'
            ),

            'title' => array(
                'value' => null,
                'description' => 'Titel',
                'description_long' => 'Titel, der in der Box angezeigt werden soll',
                'wp_option' => 'imbaf_shortcode_review_box_title'),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll.'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard System-Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet('shortcode_buy_button',$default_template);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);



        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        } else {

            $this -> post_id = get_the_ID();

        }



        $text = get_post_meta($this->post_id,'_imbaf_review_text',true);
        $text = apply_filters('the_excerpt',$text);



        $stars = get_post_meta($this->post_id,'_imbaf_review_star_rating',true);

        if($text == null && $stars == null) {


            return '';

        }

        $starclass = $stars * 10;

        $data = array(
            'text' => $text,
            'stars' => $stars,
            'starclass' => $starclass
        );

        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        return $output;






    }

    /* Vergleichstabelle */

    function comptable($args){


        $default_template = 'shortcode_comptable';

        $default_args = array(
            'product1' => array(
                'value' => null,
                'description' => 'Vergleichsprodukt 1',
                'type' => 'product'
            ),

            'product2' => array(
                'value' => null,
                'description' => 'Vergleichsprodukt 2',
                'type' => 'product'
            ),

            'product3' => array(
                'value' => null,
                'description' => 'Vergleichsprodukt 3',
                'type' => 'product'
            ),

            'product4' => array(
                'value' => null,
                'description' => 'Vergleichsprodukt 4',
                'type' => 'product'
            ),

            'highlight' => array(
                'value' => null,
                'description' => 'Kommagetrennte Produkt-IDs für Produkte, die gekennzeichnet werden sollen',
                'description_long' => 'Kommagetrennte Produkt-IDs für Produkte, die gekennzeichnet werden sollen',
            ),

            'display_buy_button' => array(
                'value' => 'yes',
                'description' => 'Kaufbutton',
                'description_long' => 'Kaufbutton anzeigen oder ausblenden',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_buy_button'
            ),

            'buy_button_text' =>array(
                'value' => 'Jetzt kaufen',
                'description' => 'Kaufbutton Beschriftung',
                'wp_option' => 'imbaf_shortcode_comptable_buy_button_text'
            ),

            'buy_button_icon' =>array(
                'value' => null,
                'description' => 'Produkt kaufen Icon',
                'wp_option' => 'imbaf_shortcode_comptable_buy_button_icon'
            ),

            'display_review_button' => array(
                'value' => 'no',
                'description' => 'Review Button', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_review_button'
            ),

            'display_price' => array(
                'value' => 'yes',
                'description' => 'Preis anzeigen', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_price'
            ),

            'display_prime_logo' => array(
                'value' => 'yes',
                'description' => 'Prime Logo bei Preisen in Vergleichstabellen anzeigen.', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_prime_logo'
            ),

            'link_prime_logo' => array(
                'value' => 'no',
                'description' => 'Prime Logo verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_comptable_link_prime_logo'
            ),

            'product_button_icon' =>array(
                'value' => null,
                'description' => 'Review Button Icon',
                'wp_option' => 'imbaf_shortcode_comptable_display_review_button_product_button_icon'),

            'product_button_text' =>array(
                'value' => 'Zur Review',
                'description' => 'Review Button Beschriftung',
                'wp_option' => 'imbaf_shortcode_comptable_product_button_text'
            ),

            'link_product_picture' => array(
                'value' => 'no',
                'description' => 'Produktbild verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_comptable_link_product_picture'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll.'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard System-Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}


        $args = shortcode_atts( $this->handle_defaults($default_args), $args);

        $args = $this -> handle_true_false($args);

        $args['highlight'] = explode(',',$args['highlight']);



        if(get_option('imbaf_enable_product_pages') != 1){

            $args['display_review_button'] = false;

        }

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet('shortcode_buy_button',$default_template);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-tables');
        wp_enqueue_style('imbaf-formats');
        wp_enqueue_style('imbaf-shadows');
        wp_enqueue_style('imbaf-comptable-values');

        wp_enqueue_script('imbaf-preisvergleich',IMBAF_PLUGIN_URL.'js/preisvergleich.js',['jquery']);

        $products = array();
        $features = array();

        for($i=1;$i<=4;$i++){

            if($args['product'.$i] != null){

                $products[$args['product'.$i]] = array('post_id' => $args['product'.$i]);

            }

        }

        foreach($products as $key => &$product){

            $this -> post_id = $product['post_id'];
            $this -> prepare_data();

            $product = $this -> product;

            if(!isset($product['id'])){

                unset($products[$key]);

            } else {

                if($product['_imbaf_custom_property_values'] != null){

                    $features = array_unique(array_merge(array_keys($product['_imbaf_custom_property_values']),$features));

                }

            }

        }



        $temp = array();

        foreach($features as $key => &$feature){

            $info = get_term_by ( 'slug', $feature, 'imbafproperties');


            if($info == false){

                unset($features[$key]);

            } else {

                $info -> meta = get_option( "taxonomy_$info->term_id" );

                $feature = array('slug' => $feature, 'name'=>$info->name, 'meta' => $info->meta, 'description' => $info->description);

                $temp[$feature['slug']] = $feature;

            }

        }

        $features = $temp;

        foreach($products as &$product){

            $types_missing = 0;

            if(count($product['_imbaf_custom_property_values']) > 0 && $product['_imbaf_custom_property_values'] != false ){


                foreach($product['_imbaf_custom_property_values'] as $type => &$val){

                    if(isset($features[$type])){

                        $product['val']['type'] = $features[$type]['meta']['imbaf_property_type'];

                        switch($product['val']['type']){


                            case 'rating':

                                if(isset($product['_imbaf_review_star_rating']) && $product['_imbaf_review_star_rating'] != ''){

                                    $val['value'] = $product['_imbaf_review_star_rating'] * 10;
                                    $val['value2'] = $product['_imbaf_review_star_rating'];
                                    $val['value3'] = $product['_imbaf_review_count'];

                                } else {

                                    unset($product['_imbaf_custom_property_values'][$type]);

                                }

                                break;

                            case 'features':

                                $val['value'] = get_post_meta($product['id'],'_imbaf_features')[0];

                                break;

                            case 'grade':



                                $grades = array(

                                    1 => 'sehr gut',
                                    2 => 'gut',
                                    3 => 'befriedigend',
                                    4 => 'ausreichend',
                                    5 => 'mangelhaft',
                                    6 => 'ungenügend'

                                );


                                $val['value2'] = $grades[floor($val['value'])];

                                break;



                        }



                    }

                    else {


                        unset($product['_imbaf_custom_property_values'][$type]);

                        $types_missing++;

                    }

                }

                if($types_missing > 0){

                    // update_post_meta($product['id'],'_imbaf_custom_property_values',$product['_imbaf_custom_property_values']);

                }

            }

        }


        $args['table_id'] = @md5(serialize($args).time().microtime());

        $data = array('products' => $products,'features' => $features);


        $data = json_encode($data);

        $data = json_decode($data,TRUE);

        $data['products'] = array_values($data['products']);


        $affiliateProduct = new \imbaa\Affilipus\Core\Affiliates\affiliateProduct();


        foreach($data['products'] as &$product){


            $product['_imbaf_features'] = [];
            $product['_imbaf_description'] = '';
            $product = $affiliateProduct->preisvergleich($product);
            $product['uniqid'] = uniqid();






        }

        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        wp_register_script( 'imbaf_comptable_mobile', IMBAF_PLUGIN_URL  . 'js/comptable-mobile.js', array('jquery'), IMBAF_VERSION, true);
        wp_enqueue_script('imbaf_comptable_mobile');

        return $output;





    }


    function comptable_big($args){

        $default_template = 'shortcode_comptable_big';

        $default_args = array(
            'products' => array(
                'value' => null,
                'description' => 'Kommagetrennte Produkt-ID',
            ),

            'highlight' => array(
                'value' => null,
                'description' => 'Kommagetrennte Produkt-IDs für Produkte, die gekennzeichnet werden sollen',
                'description_long' => 'Kommagetrennte Produkt-IDs für Produkte, die gekennzeichnet werden sollen',
            ),

            'display_buy_button' => array(
                'value' => 'yes',
                'description' => 'Kaufbutton',
                'description_long' => 'Kaufbutton anzeigen oder ausblenden',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_buy_button'
            ),

            'buy_button_text' =>array(
                'value' => 'Jetzt kaufen',
                'description' => 'Kaufbutton Beschriftung',
                'wp_option' => 'imbaf_shortcode_comptable_buy_button_text'
            ),

            'buy_button_icon' =>array(
                'value' => null,
                'description' => 'Produkt kaufen Icon',
                'wp_option' => 'imbaf_shortcode_comptable_buy_button_icon'
            ),

            'display_review_button' => array(
                'value' => 'no',
                'description' => 'Review Button', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_review_button'
            ),

            'display_price' => array(
                'value' => 'yes',
                'description' => 'Preis anzeigen', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_price'
            ),

            'display_prime_logo' => array(
                'value' => 'yes',
                'description' => 'Prime Logo bei Preisen in Vergleichstabellen anzeigen.', 'type' => 'select',
                'options' => array(
                    array('option_text' => 'anzeigen', 'option_value' => 'yes'),
                    array('option_text' => 'nicht anzeigen', 'option_value' => 'no')
                ),
                'wp_option' => 'imbaf_shortcode_comptable_display_prime_logo'
            ),

            'link_prime_logo' => array(
                'value' => 'no',
                'description' => 'Prime Logo verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_comptable_link_prime_logo'
            ),

            'product_button_icon' =>array(
                'value' => null,
                'description' => 'Review Button Icon',
                'wp_option' => 'imbaf_shortcode_comptable_display_review_button_product_button_icon'),

            'product_button_text' =>array(
                'value' => 'Zur Review',
                'description' => 'Review Button Beschriftung',
                'wp_option' => 'imbaf_shortcode_comptable_product_button_text'
            ),

            'link_product_picture' => array(
                'value' => 'no',
                'description' => 'Produktbild verlinken',
                'description_long' => '',
                'type' => 'select',
                'options' => array(
                    array('option_text' => 'nein', 'option_value' => 'no'),
                    array('option_text' => 'Zur Review', 'option_value' => 'review'),
                    array('option_text' => 'Zur Kaufseite', 'option_value' => 'product'),
                ),
                'wp_option' => 'imbaf_shortcode_affilipus_comptable_link_product_picture'
            ),

            'template' => array(
                'value' => $default_template,
                'description' => 'Template, das verwendet werden soll.'
            ),

            'default_template' => array(
                'value' => $default_template,
                'description' => 'Standard System-Template',
                'internal' => true
            )
        );

        if(isset($args['get_params'])){return $default_args;}


        $args = shortcode_atts( $this->handle_defaults($default_args), $args);

        $args = $this -> handle_true_false($args);

        $args['products'] = explode(',',$args['products']);
        $args['highlight'] = explode(',',$args['highlight']);

        if(get_option('imbaf_enable_product_pages') != 1){

            $args['display_review_button'] = false;

        }

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet('shortcode_buy_button',$default_template);

        $this -> sideload_stylesheet($args['template'],'imbaf-custom-'.$args['template']);
        $this -> sideload_stylesheet($default_template,'imbaf-'.$default_template);

        wp_enqueue_style('imbaf-buttons');
        wp_enqueue_style('imbaf-tables');
        wp_enqueue_style('imbaf-formats');
        wp_enqueue_style('imbaf-shadows');
        wp_enqueue_style('imbaf-comptable-values');


        $products = array();
        $features = array();


        foreach($args['products'] as $product){


            $products[$product] = array('post_id' => $product);

        }




        foreach($products as $key => &$product){

            $this -> post_id = $product['post_id'];
            $this -> prepare_data();

            $product = $this -> product;

            if(!isset($product['id'])){

                unset($products[$key]);

            } else {

                if($product['_imbaf_custom_property_values'] != null){

                    $features = array_unique(array_merge(array_keys($product['_imbaf_custom_property_values']),$features));

                }

            }

        }



        $temp = array();

        foreach($features as $key => &$feature){

            $info = get_term_by ( 'slug', $feature, 'imbafproperties');


            if($info == false){

                unset($features[$key]);

            } else {

                $info -> meta = get_option( "taxonomy_$info->term_id" );

                $feature = array('slug' => $feature, 'name'=>$info->name, 'meta' => $info->meta, 'description' => $info->description);

                $temp[$feature['slug']] = $feature;

            }

        }

        $features = $temp;

        foreach($products as &$product){

            $types_missing = 0;

            if(count($product['_imbaf_custom_property_values']) > 0 && $product['_imbaf_custom_property_values'] != false ){


                foreach($product['_imbaf_custom_property_values'] as $type => &$val){

                    if(isset($features[$type])){

                        $product['val']['type'] = $features[$type]['meta']['imbaf_property_type'];

                        switch($product['val']['type']){


                            case 'rating':

                                if(isset($product['_imbaf_review_star_rating']) && $product['_imbaf_review_star_rating'] != ''){

                                    $val['value'] = $product['_imbaf_review_star_rating'] * 10;
                                    $val['value2'] = $product['_imbaf_review_star_rating'];
                                    $val['value3'] = $product['_imbaf_review_count'];

                                } else {

                                    unset($product['_imbaf_custom_property_values'][$type]);

                                }

                                break;

                            case 'features':

                                $val['value'] = get_post_meta($product['id'],'_imbaf_features')[0];

                                break;

                            case 'grade':



                                $grades = array(

                                    1 => 'sehr gut',
                                    2 => 'gut',
                                    3 => 'befriedigend',
                                    4 => 'ausreichend',
                                    5 => 'mangelhaft',
                                    6 => 'ungenügend'

                                );


                                $val['value2'] = $grades[floor($val['value'])];

                                break;



                        }



                    }

                    else {


                        unset($product['_imbaf_custom_property_values'][$type]);

                        $types_missing++;

                    }

                }

                if($types_missing > 0){

                    // update_post_meta($product['id'],'_imbaf_custom_property_values',$product['_imbaf_custom_property_values']);

                }

            }

        }


        $args['table_id'] = @md5(serialize($args).time().microtime());

        $data = array('products' => $products,'features' => $features);


        $data = json_encode($data);

        $data = json_decode($data,TRUE);

        $data['products'] = array_values($data['products']);


        foreach($data['products'] as &$product){


            $product['_imbaf_features'] = [];
            $product['_imbaf_description'] = '';


        }


        $output = $this -> sideload_template($args['template'],$default_template,$args,$data);

        wp_register_script( 'imbaf_comptable_mobile', IMBAF_PLUGIN_URL  . 'js/comptable-mobile.js', array('jquery'), IMBAF_VERSION, true);
        wp_enqueue_script('imbaf_comptable_mobile');

        return $output;





    }

}
