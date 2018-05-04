<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ExternalReview {




    public static function metabox($post){

        $p = new Affiliates\affiliateProduct();

        $product = $p -> loadProductById($post->ID);

        if($product == false){

            echo "<p>Bitte speichere deinen Entwurf, bevor du fortfährst.</p>";

            return false;
        }


        ?>



        <table class="widefat">


            <tbody>

            <tr>

                <td>
                    <label for="imbaf_external_review[link]">Abweichende Produktseite</label><br><br>
                    <input class="widefat" id="imbaf_external_review[link]" type="text" name="imbaf_external_review[link]" placeholder="http://" value="<?php if(isset($product['_imbaf_external_review']['link'])) { echo $product['_imbaf_external_review']['link'];} ?>" style="width:100%;">
                </td>
                <td>

                    <label for="imbaf_external_review[rel]">Link follow/nofollow</label><br><br>
                    <select class="widefat" name="imbaf_external_review[rel]">

                        <option value="follow" <?php if($product['_imbaf_external_review']['rel'] == 'follow'){ echo "selected";} ?>>Follow (Standard)</option>
                        <option value="nofollow"  <?php if($product['_imbaf_external_review']['rel'] == 'nofollow'){ echo "selected";} ?>>Nofollow</option>

                    </select>

                </td>

            </tr>

            <tr>

                <td colspan="3">

                    <p class="description">Wenn dieser Wert gesetzt wird, werden alle Produkt-Review Links auf diese Seite anstatt zur Produktseite umgeleitet.</p>
                    <p class="description">Das ist besonders in Kombination mit deaktivierten Produktseiten nützlich. So kannst du Affilipus Shortcodes benutzen, ohne die Produktseiten nutzen zu müssen und kannst dabei auf Reviews, die beispielsweise Blog-Beiträge sein können verweisen.</p>


                </td>

            </tr>

            </tbody>

        </table>





        <?php

    }


    public static function save($post_id){


        if(isset($_POST['imbaf_external_review'])){


            update_post_meta( $post_id, '_imbaf_external_review', $_POST['imbaf_external_review']);
            wp_cache_delete( 'imbaf_product_'.$post_id ,'imbaf_products');


        }
      

    }







}