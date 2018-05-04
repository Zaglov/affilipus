<?php

namespace imbaa\Affilipus\Admin\PostType\Metaboxes;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductCDNPictures {




    public static function metabox($post){

        wp_register_script( 'imbaf_admin_cdn_pictures', IMBAF_PLUGIN_URL  . 'js/admin-cdn-pictures.js', array('jquery'), '1.0', true);

        wp_enqueue_script('imbaf_admin_cdn_pictures');

        wp_localize_script(
            'imbaf_admin_products',
            'ajax_object',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' )
            )
        );

        $p = new Affiliates\affiliateProduct();
        $product = $p -> loadProductById($post->ID);

        if(isset($product['_imbaf_cdn_pictures']) && count($product['_imbaf_cdn_pictures']) != 0){

            global $wpdb;


            if(count($product['_imbaf_cdn_pictures'] > 0)){

                foreach($product['_imbaf_cdn_pictures'] as &$pictureset){


                    $pictureset = array(


                        'images' => $pictureset,
                        'imported' => 0

                    );


                    foreach($pictureset['images'] as $key => &$picture) {





                        if(!is_array($picture)){

                            if($product['_imbaf_affiliate'] == 'amazon'){


                                $url = $picture;
                                $url2 = str_replace('https://images-na.ssl-images-amazon.com/images/','http://ecx.images-amazon.com/images/',$picture);
                                $check = $wpdb->get_results( "SELECT COUNT(*) AS import_count FROM {$wpdb->postmeta} WHERE meta_value = '{$url}' OR meta_value = '{$url2}' AND meta_key = 'imbaf_source_url'" );

                            } else {

                                $check = $wpdb->get_results( "SELECT COUNT(*) AS import_count FROM {$wpdb->postmeta} WHERE meta_value = '{$picture}' AND meta_key = 'imbaf_source_url'" );

                            }

                            $url = $picture;
                            $picture = array();

                            if($check[0] -> import_count > 0){


                                $pictureset['imported'] = 1;
                                $picture['imported'] = 1;


                            } else {

                                $picture['imported'] = 0;

                            }

                            $picture['url'] = $url;

                        } else {


                            unset($pictureset['images'][$key]);

                        }







                    }


                    $pictureset['preview'] = null;

                    if ( ! empty( $pictureset['images']['small']['url'] ) && $pictureset['images']['small']['url'] != '' ) {

                        $pictureset['preview'] = $pictureset['images']['small']['url'];

                    } else if ( ! empty( $pictureset['images']['medium']['url'] ) && $pictureset['images']['medium']['url'] != '' ) {

                        $pictureset['preview'] = $pictureset['images']['medium']['url'];

                    } else {

                        $pictureset['preview'] = $pictureset['images']['large']['url'];

                    }


                }

            }




            ?>





            <table class="wp-list-table widefat striped" id="imbaf_cdn_pictures">

                <tr>
                    <td width="50">Bild</td>
                    <td>URLs</td>
                    <td>Importieren</td>
                </tr>



                <?php

                if(count($product['_imbaf_cdn_pictures'] > 0)) {

                    foreach ( $product['_imbaf_cdn_pictures'] as $key => $set ) {

                        ?>

                        <tr id="imbaf_cdn_picture_<?php echo $key; ?>">


                            <td><img src="<?php echo $set['preview']; ?>"></td>
                            <td>


                                <table style="width:100%;">

                                    <?php


                                    foreach ( $set['images'] as $pic_size => $pic ) { ?>

                                        <tr>
                                            <td width="100"><?php echo $pic_size; ?></td>
                                            <td><input type="text" value="<?php echo $pic['url'] ?>" readonly
                                                       style="width:100%;"></td>
                                        </tr>
                                    <?php } ?>

                                </table>

                            </td>
                            <td style="max-width: 75px;">


                                <?php if ( $set['imported'] == 0 ) { ?>

                                    <button class="button-primary importing"
                                            style="width:100%; margin-top:10px; display:none;"
                                            disabled>Importiere
                                    </button>
                                    <button class="button-primary importAction" data-action="import_cdn_picture"
                                            data-picture-id="<?php echo $key; ?>"
                                            data-post-id="<?php echo $post->ID; ?>"
                                            style="width:100%; margin-top:10px;">Importieren
                                    </button>


                                    <div id="imbaf_cdn_picture_success_<?php echo $key; ?>"
                                         class="imbaf_cdn_picture_success"
                                         style="display:none;">

                                        <p>Das Bild wurde erfolgreich importiert. Damit es im Backend angezeigt wird,
                                            muss
                                            gegebenenfalls die Seite aktualisiert werden.</p>

                                    </div>

                                <?php } else { ?>

                                    <p>Das bild ist bereits in deiner Mediathek hinterlegt.</p>

                                    <?php

                                }

                                ?>


                            </td>

                        </tr>

                        <?php


                    }


                }


                ?>





            </table>


            <?php


        } else {


            echo "Keine CDN Bilder für Import verfügbar.";


        }

    }


    public static function save($post_id){



      

    }







}