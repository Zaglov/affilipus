<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductDescription {




    public static function metabox($post){

        $value = get_post_meta ($post->ID,'_imbaf_description',true);
        wp_editor( htmlspecialchars_decode($value), 'imbaf_description', $settings = array('textarea_name'=>'imbaf[description]') );


        ?>

        <p class="description">The Product description is available through the [affilipus_default_description] Shortcode.</p>

        <?php

    }


    public static function save($post_id){



        if(isset($_POST['imbaf']['description'])){


            update_post_meta( $post_id, '_imbaf_description', $_POST['imbaf']['description']);


        }

wp_cache_delete( 'imbaf_product_'.$post_id ,'imbaf_products');


}







}