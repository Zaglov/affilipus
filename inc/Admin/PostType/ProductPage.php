<?php

namespace imbaa\Affilipus\Admin\PostType;
use imbaa\Affilipus\Core as CORE;
use imbaa\Affilipus\Core\Affiliates as Affiliates;

class ProductPage{


   function  __construct(){


       add_action( 'add_meta_boxes', array($this,'add_metaboxes') );

      // add_action( 'save_post', [Metaboxes\Preisvergleich::class,'save'] );
       add_action( 'save_post', [Metaboxes\ProductReview::class,'save'] );
       add_action( 'save_post', [Metaboxes\ExternalReview::class,'save']  );
       add_action( 'save_post', [Metaboxes\ProductProperties::class,'save'] );
       add_action( 'save_post', [Metaboxes\ProductDescription::class,'save'] );
       add_action( 'save_post', [Metaboxes\ProductPictures::class,'save'] );
       add_action( 'save_post', [Metaboxes\ProductReflinks::class,'save'] );


    }


    function add_metaboxes(){


        /* add_meta_box( $id, $title, $callback, $post_type, $context,$priority, $callback_args );*/

        add_meta_box(
            'imbaf_product_properties', // ID der Metabox
            __( 'Affilipus Produkt Eigenschaften', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductProperties::class,'metabox'], // Callbackfunktion der Metabox
            'imbafproducts', // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_description', // ID der Metabox
            __( 'Affilipus Produkt Beschreibung', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductDescription::class,'metabox'], // Callbackfunktion der Metabox
            'imbafproducts', // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_reflinks', // ID der Metabox
            __( 'Affilipus Produkte und Reflinks', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductReflinks::class,'metabox'], // Callbackfunktion der Metabox
            'imbafproducts', // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_cdn_pictures', // ID der Metabox
            __( 'Affilipus CDN Bilder', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductCDNPictures::class,'metabox'], // Callbackfunktion der Metabox
            'imbafproducts', // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'low' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_shortcode_generator', // ID der Metabox
            __( 'Affilipus Shortcode Generator', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ShortcodeGenerator::class,'metabox'], // Callbackfunktion der Metabox
            array( 'imbafproducts', 'post', 'page' ), // Post Typ, in der die Metabox erscheinen soll
            'side', // Wo soll sie erscheinen?
            'default' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_review', // ID der Metabox
            __( 'Produkt Review', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductReview::class,'metabox'] , // Callbackfunktion der Metabox
            array('imbafproducts'), // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_external_review', // ID der Metabox
            __( 'Produktseiten Einstellungen', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ExternalReview::class,'metabox'], // Callbackfunktion der Metabox
            array('imbafproducts'), // Post Typ, in der die Metabox erscheinen soll
            'normal', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );

        add_meta_box(
            'imbaf_product_picture', // ID der Metabox
            __( 'Produktbild', IMBAF_TEXT_DOMAIN ), // Name der Metabox
            [Metaboxes\ProductPictures::class,'metabox'], // Callbackfunktion der Metabox
            array('imbafproducts'), // Post Typ, in der die Metabox erscheinen soll
            'side', // Wo soll sie erscheinen?
            'high' // Priorität der Metabox
        );



        if(AFP_DEBUG == true) {




            add_meta_box(
                'imbaf_product_debug', // ID der Metabox
                __( 'Produkt Debug Info', IMBAF_TEXT_DOMAIN ), // Name der Metabox
                [Metaboxes\ProductDebug::class,'metabox'], // Callbackfunktion der Metabox
                array( 'imbafproducts' ), // Post Typ, in der die Metabox erscheinen soll
                'normal', // Wo soll sie erscheinen?
                'high' // Priorität der Metabox
            );

        }


    }


}
