<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductProperties {




    public static function metabox($post){


        wp_register_script( 'imbaf_admin_features', IMBAF_PLUGIN_URL  . 'js/admin-features.js', array('jquery'), '1.0', true);
        wp_enqueue_script('imbaf_admin_features');

        wp_register_script( 'imbaf_admin_properties', IMBAF_PLUGIN_URL  . 'js/admin-properties.js', array('jquery','knockout','knockout-viewmodel'), '1.0', true);


        wp_localize_script(
            'imbaf_admin_properties',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
            )
        );

        wp_localize_script(
            'imbaf_admin_properties',
            'localize',
            array(
                'post_id' => $post->ID,

            )
        );

        wp_enqueue_script('imbaf_admin_properties');

        $p = new Affiliates\affiliateProduct();

        $product = $p -> loadProductById($post->ID);


        if($product == false){

            echo "<p>Bitte speichere deinen Entwurf, bevor du fortfährst.</p>";

            return false;
        }

        ?>

    <div>

        <h4>Produkteigenschaften</h4>

        <div id="imbaf_product_properties">


            <!--<pre data-bind="text: ko.toJSON($data, null, 2)"></pre>-->


            <ul class="sortable ui-sortable properties_list" data-bind="foreach: $data.allProperties">

                <li data-bind="if: $data.selected()">
                    <table class="widefat">

                        <tr>

                            <td style="width: 50px; padding:0px; vertical-align:middle;"><div class="hndle left" style="padding: 0px; width: 100%; text-align:center; border-bottom: 0px;"><span class="dashicons dashicons-menu"></span></div></td>
                            <th width="150" data-bind="text: $data.name"></th>
                            <td>


                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"text\""-->

                                <input type="text" data-bind="value: $data.value" style="width: 100%;">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"bool\""-->

                                <input type="checkbox" data-bind="checked: $data.value">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"number\""-->

                                <input type="number" step="0.01" data-bind="value: $data.value" style="width: 100%;">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"rating\""-->

                                <input type="hidden" value='rating' style="width: 100%;">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"fromto\""-->

                                <input type="number" step="0.01" data-bind="value: $data.value">
                                <input type="number" step="0.01" data-bind="value: $data.value2">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"grade\""-->

                                <input type="number" step="0.01" min="1" max="6" data-bind="value: $data.value">

                                <!-- /ko -->

                                <!-- ko if: ko.toJSON($data.meta.imbaf_property_type, null, 2) == "\"features\""-->

                                Zum Produkt hinterlegte Features

                                <!-- /ko -->

                                <!--<div data-bind="text: $data.meta.imbaf_property_type"></div>-->

                                <input type="hidden" data-bind="value:$data.slug" name="imbaf_custom_properties[type][]">
                                <input type="hidden" data-bind="value:$data.term_id" name="imbaf_custom_properties[term_id][]">

                                <input type="hidden" data-bind="value:$data.value" name="imbaf_custom_properties[value][]">
                                <input type="hidden" data-bind="value:$data.value2" name="imbaf_custom_properties[value2][]">

                            </td>
                            <td style="width: 50px; vertical-align:middle; text-align:center;" data-bind="text: $data.meta.imbaf_property_suffix"></td>
                            <td width="50" valign="middle">

                                <button class="button button-primary" data-bind="click: function($data){$root.deleteProperty($data)}">Löschen</button>

                            </td>

                        </tr>

                    </table>
                </li>

            </ul>


            <div data-bind="foreach: $data.allProperties">

                <button data-bind="disable: $data.selected, text: $data.name, click: function($data){$root.addProperty($data)}" class="button button-default" style="margin: 5px;"></button>

            </div>

            <input type="hidden" name="imbaf_custom_properties[dummy]" value="1">

            <p class="description" style="margin-top:5px;">Bekanntes Problem vom Firefox: Es kann vorkommen, dass Produkteigenschaften sich nicht bearbeiten lassen, weil du nicht in das Textfeld reinklicken kannst. Versuche es dann mit einem Rechtsklick.</p>



        </div>

        <h4>Allgemeine Eigenschaften</h4>

        <table class="wp-list-table widefat striped">


            <tr>

                <td>Affilipus Produkt-ID</td>
                <td><?php echo $product['id']; ?></td>

            </tr>

            <tr>

                <td>Partner</td>
                <td>

                    <?php


                    $partner = get_post_meta($post->ID,'_imbaf_affiliate',true);
                    $partner_identifier = get_post_meta($post->ID,'_imbaf_affiliate_identifier',true);


                    switch($partner){


                        case 'amazon':

                            echo "<img class='shopLogo' src='".IMBAF_PLUGIN_URL.'images/affiliates/amazon_'.$partner_identifier.'.png'."'>";

                            break;


                        case 'affilinet':


                            $shop_info = get_option('_imbaf_'.$partner.'_shop_info_'.$partner_identifier);


                            echo "<img class='shopLogo' alt='{$shop_info->ShopTitle}' src='{$shop_info->Logo->URL}'>";
                            echo "<br>";
                            echo $shop_info->ShopTitle;

                            break;


                        case 'zanox':

                            $shop_info = get_option('imbaf_zanox_shop_'.$partner_identifier);

                            echo "<img class='shopLogo' alt='".$shop_info['name']."' src='".$shop_info['logo']."'><br>";
                            echo $shop_info['name'];

                            break;

                        default:


                            echo "Eigener";


                            break;

                    }

                    ?>

                </td>
            </tr>


            <?php

            if(isset($product['_imbaf_asin']) && $product['_imbaf_asin'][0] != '') {?>

                <tr>
                    <td>ASIN</td>
                    <td><?php echo $product['_imbaf_asin']; ?></td>
                </tr>

                <?php

            } ?>


            <tr>
                <td>EAN</td>
                <td><input type="text" placeholder="EAN" name="imbaf_ean[]" value="<?php if(isset($product['_imbaf_ean'][0])){echo $product['_imbaf_ean'][0];} ?>"></td>
            </tr>


            <?php if(isset($product['_imbaf_ean']) && count($product['_imbaf_ean']) > 1 ) {?>

                <tr>

                    <td>Weitere EAN</td>
                    <td>




                        <ul>
                            <?php



                            foreach($product['_imbaf_ean'] as $key => $ean){

                                if($key > 0){

                                    echo '<li><input type="text" placeholder="EAN" name="imbaf_ean[]" value="'.$ean.'"></li>';

                                }

                            }


                            ?>



                            <li><input type="text" placeholder="Zusätzliche EAN" name="imbaf_ean[]"></li>
                            <li><input type="text" placeholder="Zusätzliche EAN" name="imbaf_ean[]"></li>
                            <li><input type="text" placeholder="Zusätzliche EAN" name="imbaf_ean[]"></li>
                            <li><input type="text" placeholder="Zusätzliche EAN" name="imbaf_ean[]"></li>

                        </ul>

                    </td>
                </tr>

            <?php } ?>


            <tr>


                <td>Lieferzeit</td>
                <td>


                    <?php

                    if ($product['_imbaf_product_shipping_detail'] == null || !isset($product['_imbaf_product_shipping_detail']['AvailabilityAttributes']['MinimumHours'])) {


                        echo 'unbekannt';

                    } else {


                        echo $product['_imbaf_product_shipping_detail']['AvailabilityAttributes']['MinimumHours'] . ' bis ' . $product['_imbaf_product_shipping_detail']['AvailabilityAttributes']['MaximumHours'] . ' Stunden';

                        echo "<br>";

                        echo 'Verfügbarkeit: ' . $product['_imbaf_product_shipping_detail']['AvailabilityAttributes']['AvailabilityType'];

                    }

                    ?>


                </td>

            </tr>




            <?php if(isset($product['_imbaf_affiliate']) && $product['_imbaf_affiliate'] == 'amazon'){ ?>

                <tr>

                    <td><img src="<?php echo IMBAF_IMAGES.'/misc/prime.png'; ?>" height="15" title="Verfügbar über Amazon Prime?"></td>
                    <td><?php


                        if(isset($product['_imbaf_product_shipping_detail']['IsEligibleForPrime'])) {

                            if($product['_imbaf_product_shipping_detail']['IsEligibleForPrime']){ echo 'ja'; } else { echo 'nein'; };

                        } else {

                            echo "unbekannt";

                        }




                        ?></td>

                </tr>

            <?php } ?>




            <tr>

                <td>Produktmaße</td>
                <td>


                    <input type="number" step="0.10" style="width:70px" name="imbaf_properties[item_dimensions_width]" placeholder="Breite" value="<?php if(isset($product['_imbaf_item_dimensions_width'])) {echo $product['_imbaf_item_dimensions_width'];} ?>"> x
                    <input type="number" step="0.10" style="width:70px" name="imbaf_properties[item_dimensions_height]" placeholder="Höhe" value="<?php if(isset($product['_imbaf_item_dimensions_height'])) { echo $product['_imbaf_item_dimensions_height'];} ?>"> x
                    <input type="number" step="0.10" style="width:70px" name="imbaf_properties[item_dimensions_length]" placeholder="Tiefe" value="<?php if(isset($product['_imbaf_item_dimensions_length'])) { echo $product['_imbaf_item_dimensions_length'];} ?>">

                    <?php if(isset($product['_imbaf_item_dimensions_unit'])) {echo $product['_imbaf_item_dimensions_unit']; } ?> (B x H x T)

                </td>

            </tr>

            <tr>
                <td>Produktgewicht</td>
                <td>
                    <input type="number" step="0.10" name="imbaf_properties[item_weight]" placeholder="Produktgewicht" value="<?php if(isset($product['_imbaf_item_weight'])) {echo $product['_imbaf_item_weight'];} ?>">
                    <?php if(isset($product['_imbaf_item_weight_unit'])){echo $product['_imbaf_item_weight_unit'];} ?>


                </td>
            </tr>

            <tr>

                <td>Verpackungsmaße</td>
                <td>


                    <input type="number" step="0.01" style="width:70px" name="imbaf_properties[package_dimensions_width]" placeholder="Breite" value="<?php if(isset($product['_imbaf_package_dimensions_width'])){ echo $product['_imbaf_package_dimensions_width'];} ?>"> x
                    <input type="number" step="0.01" style="width:70px" name="imbaf_properties[package_dimensions_height]" placeholder="Höhe" value="<?php if(isset($product['_imbaf_package_dimensions_width'])){echo $product['_imbaf_package_dimensions_height'];} ?>"> x
                    <input type="number" step="0.01" style="width:70px" name="imbaf_properties[package_dimensions_length]" placeholder="Tiefe" value="<?php if(isset($product['_imbaf_package_dimensions_width'])){echo $product['_imbaf_package_dimensions_length'];} ?>">

                    <?php if(isset($product['_imbaf_package_dimensions_unit'])) {echo $product['_imbaf_package_dimensions_unit'];} ?> (B x H x T)

                </td>

            </tr>

            <tr>
                <td>Gewicht mit Verpackung</td>
                <td>

                    <input type="number" step="0.01" name="imbaf_properties[package_weight]" placeholder="Gewicht mit Verpackung" value="<?php if(isset($product['_imbaf_package_weight'])){echo $product['_imbaf_package_weight'];} ?>">
                    <?php if(isset($product['_imbaf_item_weight_unit'])){echo $product['_imbaf_item_weight_unit'];} ?>


                </td>
            </tr>

            <tr>
                <td>Letztes Preis Update</td>
                <td><?php if(!isset($product['_imbaf_last_price_update']) || $product['_imbaf_last_price_update'] == ''){} else {echo date('d.m.Y H:i:s',strtotime($product['_imbaf_last_price_update']));} ?></td>
            </tr>



        </table>

        <h4>Features</h4>

        <div id="imbaf_feature_list">


            <script id="imbaf_feature_template" type="text">

                    <?php


                echo '<li>';

                echo '<table class="widefat">';
                echo '<tr>';
                echo '<td style="width: 50px; padding:0px; vertical-align:middle;">';
                echo '<div class="hndle left" style="padding: 0px; width: 100%; text-align:center; border-bottom: 0px;"><span class="dashicons dashicons-menu"></span></div>';
                echo '</td>';
                echo '<td>';
                echo '<input type="text" name="imbaf_properties[features][]" class="widefat" placeholder="Feature Name" style="">';
                echo '</td>';
                echo '<td style="width: 70px;">';
                echo '<input type="button" class="button-primary" data-action="delete_imbaf_feature" value="Löschen" style="float:right; width:100%;">';
                echo '</td>';

                echo '</tr>';
                echo '</table>';

                echo '</li>';

                ?>

                </script>


            <?php



            echo '<ul class="sortable  ui-sortable feature_list">';

            if(isset($product['_imbaf_features']) && count($product['_imbaf_features']) > 0 && $product['_imbaf_features'] != false){




                foreach ($product['_imbaf_features'] AS $key => $feature) {

                    echo '<li>';

                    echo '<table class="widefat">';
                    echo '<tr>';
                    echo '<td style="width: 50px; padding:0px; vertical-align:middle;">';
                    echo '<div class="hndle left" style="padding: 0px; width: 100%; text-align:center; border-bottom: 0px;"><span class="dashicons dashicons-menu"></span></div>';
                    echo '</td>';
                    echo '<td>';
                    echo '<input type="text" name="imbaf_properties[features][]" value="'.$feature.'" class="widefat" placeholder="Feature Name" style="">';
                    echo '</td>';
                    echo '<td style="width: 70px;">';
                    echo '<input type="button" class="button-primary" data-action="delete_imbaf_feature" value="Löschen" style="float:right; width:100%;">';
                    echo '</td>';

                    echo '</tr>';
                    echo '</table>';

                    echo '</li>';

                }



            }

            echo '</ul>';



            ?>

            <input type="button" class="button-primary" id="new_feature_add" style="width: 100%; margin-top:10px;" value="Feature Hinzufügen" >

            <p class="description" style="margin-top:5px;">Bekanntes Problem vom Firefox: Es kann vorkommen, dass Features sich nicht bearbeiten lassen, weil du nicht in das Textfeld reinklicken kannst. Versuche es dann mit einem Rechtsklick.</p>

        </div>

        <script type="text/javascript">
            jQuery(document).ready(function($)
            {
                $( '.sortable' ).sortable({
                    opacity: 0.6,
                    revert: true,
                    cursor: 'move',
                    handle: '.hndle',
                    placeholder: {
                        element: function(currentItem) {
                            return $("<li style='background:#E7E8AD'>&nbsp;</li>")[0];
                        },
                        update: function(container, p) {
                            return;
                        }
                    }
                });
                $( '.sortable' ).disableSelection();
            });
        </script>


    </div>

<?php



}


    public static function save( $post_id ) {


        if(isset($_POST['post_type']) && $_POST['post_type'] == 'imbafproducts'){

            if(isset($_POST['imbaf_price'])){

                foreach($_POST['imbaf_price'] as $product_id => $prices){


                    if(isset($prices['offering_price'])){


                        update_post_meta($product_id,'_imbaf_selected_price','offering_price');

                    } else {

                        update_post_meta($product_id,'_imbaf_selected_price','list_price');


                    }


                    update_post_meta( $product_id, '_imbaf_price', $prices);
                    update_post_meta( $product_id, '_imbaf_last_price_update', date('Y-m-d G:i:s'),time());


                }

            }


            if ( array_key_exists('imbaf_properties', $_POST ) ) {

                $data = $_POST['imbaf_properties'];

                foreach($data as $key => $meta){

                    update_post_meta( $post_id, '_imbaf_'.$key, $meta);

                }

                if(!isset($data['features'])){

                    update_post_meta( $post_id, '_imbaf_features', null);

                }

            }



            if ( array_key_exists('imbaf_custom_properties', $_POST )) {


                unset($_POST['imbaf_custom_properties']['dummy']);
                $prev_data = get_post_meta($post_id,'_imbaf_custom_property_values');



                $data = array();


                if(count($_POST['imbaf_custom_properties']) > 0){

                    foreach($_POST['imbaf_custom_properties']['type'] as $key => $property){


                        if(!array_key_exists($key,$_POST['imbaf_custom_properties']['value'])){

                            $_POST['imbaf_custom_properties']['value'][$key] = null;

                        }

                        if(!array_key_exists($key,$_POST['imbaf_custom_properties']['value2'])){

                            $_POST['imbaf_custom_properties']['value2'][$key] = null;

                        }

                        $data[$property] = array('position'=>$key,'value'=>$_POST['imbaf_custom_properties']['value'][$key], 'value2' => $_POST['imbaf_custom_properties']['value2'][$key]);


                        wp_set_object_terms( $post_id, $property, 'imbafproperties', true);

                    }

                    update_post_meta( $post_id, '_imbaf_custom_property_values', $data);

                } else {

                    update_post_meta( $post_id, '_imbaf_custom_property_values', null);
                }




            }

            else {

                //update_post_meta( $post_id, '_imbaf_custom_property_values', null);

            }


            $eans = get_post_meta($post_id,'_imbaf_ean');


            foreach($eans as $ean){


                delete_post_meta($post_id,'_imbaf_ean',$ean);


            }

            if(array_key_exists('imbaf_ean',$_POST)){


                foreach($_POST['imbaf_ean'] as $ean) {

                    if(strlen($ean) != 0){

                        add_post_meta($post_id, '_imbaf_ean', $ean);

                    }

                }

            }

        }

        wp_cache_delete( 'imbaf_product_'.$post_id ,'imbaf_products');


    }







}