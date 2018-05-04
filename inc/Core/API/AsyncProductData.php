<?php

namespace imbaa\Affilipus\Core\API;


class AsyncProductData {

    function __construct(){

        add_action('wp_ajax_imbaf_amazon_reviews_link', array($this,'amazon_reviews_link') );
        add_action('wp_ajax_nopriv_imbaf_amazon_reviews_link', array($this,'amazon_reviews_link') );

    }


    function amazon_reviews_link(){


        $products = new \imbaa\Affilipus\Core\Output\imbafShortcodeBasics();

        $data = $products -> prepare_data($_POST['product_id']);


        echo '<iframe frameborder="0" width="100%"  class="amazonReviewsIframe" src="'.$data['_imbaf_reviews_iframe_link'].'" height="2000"></iframe>';


        wp_die();

    }

}