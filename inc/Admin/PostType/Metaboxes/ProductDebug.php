<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductDebug {


    function __construct(){



    }


    public static function metabox($post){


        $p = new Affiliates\affiliateProduct();
        $product = $p -> loadProductById($post->ID);

        echo $post->ID;

        echo "<pre>";

        print_r($product);

        echo "</pre>";

    }


}