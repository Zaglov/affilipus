<?php
namespace imbaa\Affilipus\Admin\OverviewPages;

class ProductOverview{


    function __construct(){


        add_action( 'admin_head', [$this,'imbaf_custom_styles'],PHP_INT_MAX);

        add_filter( 'manage_edit-imbafproducts_columns', [$this,'edit_imbafproducts_columns'] , 1) ;
        add_action( 'manage_imbafproducts_posts_custom_column', [$this,'output_imbafproducts_columns'], 10, 2 );

    }


    function imbaf_custom_styles(){

        $output_css = '<style type="text/css">
       
        #date {max-width: 120px !important;}
        #identification {max-width: 120px !important;}
        #price {max-width: 150px !important;}
        #partner {max-width: 40px !important;}
        .column-partner {text-align: center; background-color:#fff;}
        .column-partner img.shopLogo {width: 100%; height: auto;}
        .column-partner img.primeStatus {width: 100%; height: auto; max-width: 60px; margin-top:10px;}
        .column-partner img.noprime {opacity: 0.5;}
        #product_id {width: 50px !important;}
       

        
    </style>';

        echo $output_css;

    }

    /**
     * Modify admin output of Affilipus Product list (table header) (inspired by A. Oestreich)
     * @since: 0.20.0
     */

    function edit_imbafproducts_columns($columns){


        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Produkt' ),
            'product_id' => 'ID',
            'price' => __( 'Preis' ),
            'partner' => __( 'Partner' ),
            'imbaftypes' => __( 'Typ' ),
            'identification' => __( 'ASIN/EAN' ),
            'imbafbrands' => __( 'Marke' ),
            'date' => __( 'Datum' )
        );

        return $columns;

    }

    /**
     * Modify admin output of Affilipus Product list (table content) (inspired by A. Oestreich)
     * @since: 0.20.0
     */

    function output_imbafproducts_columns( $column, $post_id ) {
        global $post;

        switch( $column ) {


            case 'product_id':

                echo $post_id;

                break;

            case 'imbaftypes' :

                /* Get the posttypes for the post. */
                $terms = get_the_terms( $post_id, 'imbaftypes' );

                /* If terms were found. */
                if ( !empty( $terms ) ) {

                    $out = array();

                    /* Loop through each term, linking to the 'edit posts' page for the specific term. */
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
                        );
                    }

                    /* Join the terms, separating them with a comma. */
                    echo join( ', ', $out );
                }

                /* If no terms were found, output a default message. */
                else {
                    _e( 'Kein Typ angegeben' );
                }

                break;

            case 'imbafbrands' :

                /* Get the posttypes for the post. */
                $terms = get_the_terms( $post_id, 'imbafbrands' );

                /* If terms were found. */
                if ( !empty( $terms ) ) {

                    $out = array();

                    /* Loop through each term, linking to the 'edit posts' page for the specific term. */
                    foreach ( $terms as $term ) {
                        $out[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
                        );
                    }

                    /* Join the terms, separating them with a comma. */
                    echo join( ', ', $out );
                }

                /* If no terms were found, output a default message. */
                else {
                    _e( 'Kein Typ angegeben' );
                }

                break;

            case 'identification':


                $partner = get_post_meta($post_id,'_imbaf_affiliate',true);
                $partner_identifier = get_post_meta($post_id,'_imbaf_affiliate_identifier',true);


                if($partner == 'amazon'){

                    /* Get the post meta. */
                    $asin = get_post_meta( $post_id, '_imbaf_asin', true );

                    /* If no duration is found, output a default message. */
                    if ( empty( $asin ) )
                        echo __( 'ASIN nicht bekannt' );
                    else
                        echo '<a href="https://www.amazon.'.$partner_identifier.'/dp/'.$asin.'" target="_blank">'.$asin.'</a><br>';

                }

                $eans = get_post_meta($post_id,'_imbaf_ean');


                if(count($eans) > 0){

                    echo "<ul>";

                    foreach($eans as $ean){

                        echo "<li>{$ean}</li>";

                    }

                    echo "</ul>";


                }
                else {

                    echo 'keine EAN';

                }


                break;

            case 'price':

                /* Get the post meta. */
                $price_meta = get_post_meta( $post_id, '_imbaf_price', true );
                $which_price = get_post_meta( $post_id, '_imbaf_selected_price', true );
                $prime_status = get_post_meta( $post_id, '_imbaf_product_shipping_detail', true );
                $last_price_update = get_post_meta($post_id, '_imbaf_last_price_update',true);

                if($price_meta != '' && count($price_meta) != 0){

                foreach ($price_meta as $price_entry) {
                    if ($price_entry['name'] == $which_price) {
                        $price = $price_entry['price'];

                        if(isset($price_entry['display_name'])){


                            $price_name = $price_entry['display_name'];


                        } else {

                            $price_name = '';

                        }


                    }

                }

                }

                /* If no duration is found, output a default message. */
                if ( empty( $price ) ) {
                    echo __( 'Preis nicht bekannt' );
                } else {


                    echo @number_format ($price, 2, ",", ".").' €';
                    echo '<div><i>'.$price_name.'</i></div>';

                }

                if($last_price_update){

                    echo '<div><br>Letztes Update: '.date('d.m.Y H:i',strtotime($last_price_update)).'</div>';

                }

                break;

            case 'partner' :


                $partner = get_post_meta($post_id,'_imbaf_affiliate',true);
                $partner_identifier = get_post_meta($post_id,'_imbaf_affiliate_identifier',true);


                switch($partner){


                    case 'amazon':


                        echo "<img class='shopLogo' src='".IMBAF_PLUGIN_URL.'images/affiliates/amazon_'.$partner_identifier.'.png'."'>";


                        $prime_status = get_post_meta($post_id, '_imbaf_product_shipping_detail', true);


                        if(array_key_exists('IsEligibleForPrime',$prime_status) && $prime_status['IsEligibleForPrime'] == true){

                            $prime_status = true;

                        } else {

                            $prime_status = false;

                        }

                        if ($prime_status) echo "<img src=\"" . IMBAF_IMAGES . "/misc/prime.png\" class=\"primeStatus\">";
                        else echo "<img class=\"primeStatus noprime\"src=\"" . IMBAF_IMAGES . "/misc/prime.png\">";


                        break;


                    case 'affilinet':


                        $shop_info = get_option('_imbaf_'.$partner.'_shop_info_'.$partner_identifier);


                        echo "<img class='shopLogo' alt='{$shop_info->ShopTitle}' src='{$shop_info->Logo->URL}'>";


                        break;


                    case 'zanox':



                        $shop_info = get_option('imbaf_zanox_shop_'.$partner_identifier);


                        echo "<img class='shopLogo' alt='".$shop_info['name']."' src='".$shop_info['logo']."'>";




                        break;


                    case 'webgains':

                        echo "WEBGAINS";

                        break;

                    default:

                        echo "Eigener";

                        break;

                }
                break;

            default :
                break;
        }
    }

}