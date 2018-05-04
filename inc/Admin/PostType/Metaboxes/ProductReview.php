<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductReview {




    public static function metabox($post){


        $p = new Affiliates\affiliateProduct();

        $product = $p -> loadProductById($post->ID);

        ?>

        <h4>Review Text</h4>


        <textarea name="imbaf_review[review_text]" style="width: 100%;" rows="10"><?php echo get_post_meta ($post->ID,'_imbaf_review_text',true); ?></textarea>

        <h4>Sterne-Bewertung</h4>


        <input style="width:150px;" type="number" min="0" max="5" step="0.10" name="imbaf_review[star_rating]" value="<?php if(isset($product['_imbaf_review_star_rating'])) {echo $product['_imbaf_review_star_rating']; } ?>"> Sterne<br>
        <input style="width:150px;" type="number" min="0" max="999999999999" step="1" name="imbaf_review[review_count]" value="<?php if(isset($product['_imbaf_review_count'])) {echo $product['_imbaf_review_count'];} ?>"> Bewertungen

        <?php

    }


    public static function save($post_id){


        if(isset($_POST['imbaf_review'])){

            update_post_meta($post_id, '_imbaf_review_star_rating', $_POST['imbaf_review']['star_rating']);
            update_post_meta($post_id, '_imbaf_review_text', $_POST['imbaf_review']['review_text']);
            update_post_meta($post_id, '_imbaf_review_count', $_POST['imbaf_review']['review_count']);

            wp_cache_delete( 'imbaf_product_'.$post_id ,'imbaf_products');

        }
      

    }







}