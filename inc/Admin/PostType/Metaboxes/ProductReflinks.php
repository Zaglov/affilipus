<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductReflinks {




    public static function metabox($post){

        $p = new Affiliates\affiliateProduct();
        $product = $p -> loadProductById($post->ID);

        wp_register_script( 'imbaf_admin_products', IMBAF_PLUGIN_URL  . 'js/admin-products.js', array('jquery'), '1.0', true);

        wp_enqueue_script('imbaf_admin_products');

        wp_localize_script(
            'imbaf_admin_products',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );

        $products = array($product);


        if($product == false){

            echo "<p>Bitte speichere deinen Entwurf, bevor du fortfährst.</p>";

            return false;
        }

        if(array_key_exists('children',$product)){

            $products = array_merge($products, $product['children']);

        }


        ?>


        <table class="wp-list-table widefat striped" id="imbaf_products">

            <tr>
                <td>Partner</td>
                <td>Produktbezeichnung</td>
                <td>Preis</td>

                <!--<td>Affiliate Links</td>-->

            </tr>

            <?php foreach($products as $pr){ ?>

                <tr>


                    <td><?php

                        if(isset($pr['_imbaf_affiliate']) && $pr['_imbaf_affiliate'] != 'imbaf_custom' ) {

                           echo '<img style="width: 100px;" src="'.$pr['_imbaf_partner_logo_url'].'">';

                        } else {


                            ?>


                            <input type="text" placeholder="Anbieter" name="imbaf[affiliate_name][<?php echo $pr['id'];?>]" value="<?php if(isset($pr['_imbaf_affiliate_name'])){echo $pr['_imbaf_affiliate_name'];} ?>">


                            <?php

                        }



                        ?></td>
                    <td>
                        <?php


                        if(isset($pr['id']) && $pr['id'] == $post->ID){
                            echo $pr['product_name'];
                        }

                        else {


                            ?>

                            <input type="text" style="width:100%; margin-bottom:5px;" name="imbaf[subproduct][<?php echo $pr['id'];?>]" value="<?php if(isset($pr['product_name'])){echo $pr['product_name'];} ?>"><br>

                            <br>
                            <input type="button" value="Löschen"  data-action="delete_product" data-product-id="<?php echo $pr['id'] ?>" class="button-primary">



                            <?php

                        }

                        ?>

                    </td>


                    <td>



                        <?php if(!isset($pr['_imbaf_price']) || count($pr['_imbaf_price']) == 0) {


                            $pr['_imbaf_price'] = [


                                'list_price' => array(

                                    'name'         => 'list_price',
                                    'display_name' => 'Listenpreis',


                                ),

                                'offering_price' => array(

                                    'name'         => 'offering_price',
                                    'display_name' => 'Angebotspreis',


                                )

                            ];


                        }

                        if(isset($pr['_imbaf_price']) && count($pr['_imbaf_price']) > 0){ ?>

<?php foreach($pr['_imbaf_price'] as $p){ ?>

                                
                                
                                <?php } ?>

                            <select style="width:100%;" name="imbaf[selected_display_prices][<?php echo $pr['id'];?>]">

                                <?php foreach($pr['_imbaf_price'] as $p){

                                    if(isset($p['name']) && isset($p['price']) && isset($p['currency'])){

                                ?>

                                    <option
                                            value="<?php echo $p['name']; ?>"
                                        <?php if(isset($p['name']) && isset($pr['_imbaf_display_price']['name'])  && $pr['_imbaf_display_price']['name'] == $p['name']){ echo 'selected';} ?>>
                                        <?php echo $p['price']; ?> <?php echo $p['currency']; ?> (<?php echo $p['display_name']; ?>)
                                    </option>

                                <?php }} ?>

                            </select>


                            <?php if(isset($pr['_imbaf_last_price_update'])){ ?>

                            <p class="description">Stand: <?php echo date('d.m.Y H:i:s',strtotime($pr['_imbaf_last_price_update'])); ?> </p>


                            <?php } ?>

                        <?php

                        }


                        if(isset($pr['_imbaf_affiliate']) && $pr['_imbaf_affiliate'] == 'imbaf_custom') { ?>

                            <ul>

                                <li>
                                    <label>Listenpreis</label><br>
                                    <input type="number" step="0.01" min="0" name="imbaf_price[<?php echo $pr['id']; ?>][list_price][price]" value="<?php if(isset($pr['_imbaf_price']['list_price'])){echo @number_format($pr['_imbaf_price']['list_price']['price'],2, '.', '');} ?>">
                                    <input type="hidden" name="imbaf_price[<?php echo $pr['id']; ?>][list_price][name]" value="list_price">
                                    <input type="hidden" name="imbaf_price[<?php echo $pr['id']; ?>][list_price][display_name]" value="Listenpreis">


                                    <select name="imbaf_price[<?php echo $pr['id']; ?>][list_price][currency]">


                                        <option value="EUR" <?php if(isset($pr['_imbaf_price']['list_price']['currency']) && $pr['_imbaf_price']['list_price']['currency'] == 'EUR') {echo 'selected';} ?>>€</option>
                                        <option value="GBP" <?php if(isset($pr['_imbaf_price']['list_price']['currency']) && $pr['_imbaf_price']['list_price']['currency'] == 'GBP') {echo 'selected';} ?>>£</option>
                                        <option value="USD" <?php if(isset($pr['_imbaf_price']['list_price']['currency']) && $pr['_imbaf_price']['list_price']['currency'] == 'USD') {echo 'selected';} ?>>US-$</option>

                                    </select>

                                </li>
                                <li>
                                    <label>Angebotspreis</label><br>
                                    <input type="number" step="0.01" min="0" name="imbaf_price[<?php echo $pr['id']; ?>][offering_price][price]" value="<?php if(isset($pr['_imbaf_price']['offering_price'])){echo @number_format($pr['_imbaf_price']['offering_price']['price'],2,'.','');} ?>">
                                    <input type="hidden" name="imbaf_price[<?php echo $pr['id']; ?>][offering_price][name]" value="offering_price">
                                    <input type="hidden" name="imbaf_price[<?php echo $pr['id']; ?>][offering_price][display_name]" value="Angebotspreis">


                                    <select name="imbaf_price[<?php echo $pr['id']; ?>][offering_price][currency]">


                                        <option value="EUR" <?php if(isset($pr['_imbaf_price']['offering_price']['currency']) && $pr['_imbaf_price']['offering_price']['currency'] == 'EUR') {echo 'selected';} ?>>€</option>
                                        <option value="GBP" <?php if(isset($pr['_imbaf_price']['offering_price']['currency']) && $pr['_imbaf_price']['offering_price']['currency'] == 'GBP') {echo 'selected';} ?>>£</option>
                                        <option value="USD" <?php if(isset($pr['_imbaf_price']['offering_price']['currency']) && $pr['_imbaf_price']['offering_price']['currency'] == 'USD') {echo 'selected';} ?>>US-$</option>

                                    </select>


                                </li>

                            </ul>


                            <?php


                        } ?>

                        <?php

                        if (isset($pr['_imbaf_affiliate']) && $pr['_imbaf_affiliate'] == 'imbaf_custom') {


                            ?>


                            <label>Reflink</label>
                            <input type='text' style="width: 100%;" name="imbaf_affiliate_links[<?php echo $pr['id']; ?>][product_page][url]" value="<?php if(isset($pr['_imbaf_affiliate_links']['product_page']['url'])){echo $pr['_imbaf_affiliate_links']['product_page']['url'];} ?>">
                            <input type='hidden' placeholder="https://" name="imbaf_affiliate_links[<?php echo $pr['id']; ?>][product_page][type]" value="product_page">

                            <?php


                        }

                        ?>



                    <!--

                    <td>

                        <?php





                        if(isset($pr['_imbaf_affiliate_links']) && count($pr['_imbaf_affiliate_links']) > 0){

                            foreach($pr['_imbaf_affiliate_links'] AS $link){


                                echo '[afp_reflink product="'.$pr['id'].'" type="'.$link['type'].'" ]Linktext[/afp_reflink]'.'<br>';

                            }

                        }





                        ?>





                    </td>

                    -->




                </tr>

            <?php } ?>

        </table>

        <?php

    }


    public static function save( $post_id ) {

        if ( array_key_exists('imbaf', $_POST ) ) {




            global $wpdb;

            $data = $_POST['imbaf'];

            if(isset($data['selected_display_prices']) && count($data['selected_display_prices'] > 0)){

                foreach($data['selected_display_prices'] AS $id => $price){

                    update_post_meta($id, '_imbaf_selected_price', $price);

                }

            }



            if(isset($data['affiliate_name']) && count($data['affiliate_name']) > 0) {
                foreach ( $data['affiliate_name'] AS $id => $title ) {


                    update_post_meta( $id, '_imbaf_affiliate_name', $title );


                }

            }


            if(isset($data['subproduct']) && count($data['subproduct']) > 0){

                foreach($data['subproduct'] AS $id => $title){

                    $q = "UPDATE {$wpdb->prefix}posts SET post_title = '{$title}' WHERE ID = {$id}";



                    $wpdb->query($q);



                }


            }


            if(!isset($data['subproduct'])){


                $q = "SELECT * FROM {$wpdb->posts} WHERE post_parent = {$post_id} AND post_type = 'imbafproducts';";

                $subProducts = $wpdb->get_results($q);

                if(count($subProducts) > 0){

                    foreach($subProducts as $sub){


                        wp_delete_post($sub->ID,true);

                    }

                }

            } else {


                $q = "SELECT * FROM {$wpdb->posts} WHERE post_parent = {$post_id} AND ID NOT IN (".implode(',',array_keys($data['subproduct'])).") AND post_type = 'imbafproducts';";

                $subProducts = $wpdb->get_results($q);

                if(count($subProducts) > 0){

                    foreach($subProducts as $sub){


                        wp_delete_post($sub->ID,true);

                    }

                }



            }


            wp_cache_delete( 'imbaf_product_'.$post_id ,'imbaf_products');


        }




        if(isset($_POST['imbaf_affiliate_links'])){



            foreach($_POST['imbaf_affiliate_links'] as $product_id => $links) {

                update_post_meta($product_id,'_imbaf_affiliate_links',$links);

            }



        }
    }







}