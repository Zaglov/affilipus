<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductPictures {


    function __construct(){



    }


    public static function metabox($post){

        $image_id = get_post_meta($post->ID,'_imbaf_product_image_id',true);
        $image = wp_get_attachment_image_src($image_id,[500,null])[0];

        wp_enqueue_media();

        wp_enqueue_script('imabef-picture-selector',IMBAF_PLUGIN_URL.'/js/productPictureSelector.js', ['jquery', 'media-upload', 'media-views'], null, true);


        ?>



        <?php if($image != false){ ?>
            <img style="width:100%; height: auto;" src="<?php echo esc_url( $image ); ?>" id="imbaf_product_picture_img"/>
        <?php } else {

            echo "<img src='' id=\"imbaf_product_picture_img\" style='width:100%;'>";
            echo "<p class=\"description\">Wähle hier das Produktbild, das anstatt des Beitragsbildes in den jeweiligen Affilipus Shortcodes angezeigt werden soll.</p>";

        } ?>
            <input name="imbaf_image[product_image_id]" id="imbaf_product_picture_image_id" type="hidden"  value="<?php echo esc_attr( $image_id ); ?>" />


            <input class="button" type="button" value="<?php _e( 'Select Image'); ?>" onclick="imbaaStorefrontImgSelector.uploader( 'imbaf_product_picture_' ); return false;"/>
            <input type="button"  class="button button-default" onclick="imbaaStorefrontImgSelector.clear('imbaf_product_picture_')" value="Entfernen">

        <?php
    }

    public static function save($post_id){





        if(array_key_exists('imbaf_image',$_POST) ) {




                add_post_meta($post_id, '_imbaf_product_image_id', $_POST['imbaf_image']['product_image_id'], true);

                update_post_meta($post_id, '_imbaf_product_image_id', $_POST['imbaf_image']['product_image_id']);









        } else {


            delete_post_meta($post_id,'_imbaf_product_image_id');

        }

    }


}