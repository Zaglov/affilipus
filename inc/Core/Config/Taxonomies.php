<?php

namespace imbaa\Affilipus\Core\Config;


class Taxonomies {


    function __construct(){

        add_action( 'init', array($this,'register_taxonomies'));


    }

    function register_taxonomies(){


        /* Register Taxonomy for Categorys */


        $slug = get_option('imbaf_brands_slug');

        register_taxonomy(
            'imbafbrands',
            array('imbafproducts'),
            array(
                'labels' => array(
                    'name'               => __('Marken',IMBAF_TEXT_DOMAIN),
                    'singular_name'      => __('Marke',IMBAF_TEXT_DOMAIN),
                    'menu_name'          => __('Marken',IMBAF_TEXT_DOMAIN),
                    'name_admin_bar'     => __('Marken',IMBAF_TEXT_DOMAIN),
                    'add_new'            => __('Neue Marke',IMBAF_TEXT_DOMAIN),
                    'add_new_item'       => __('Neue Marke anlegen',IMBAF_TEXT_DOMAIN),
                    'edit_item'          => __('Marke bearbeiten',IMBAF_TEXT_DOMAIN),
                    'new_item'           => __('Neue Marke',IMBAF_TEXT_DOMAIN),
                    'view_item'          => __('Marke ansehen',IMBAF_TEXT_DOMAIN),
                    'search_items'       => __('Marke suchen',IMBAF_TEXT_DOMAIN),
                    'not_found'          => __('Marke nicht gefunden',IMBAF_TEXT_DOMAIN),
                    'not_found_in_trash' => __("Keine Marken im Papierkorb",IMBAF_TEXT_DOMAIN),
                    'all_items'          => __("Alle Marken",IMBAF_TEXT_DOMAIN)
                ),

                'rewrite' => array( 'slug' => $slug, 'hierarchical' => true ),
                'public' => get_option( 'imbaf_enable_product_pages' ),
                'show_in_nav_menus' => true,
                'show_ui' => true,
                'show_tagcloud' => true,
                'hierarchical' => true,
            )
        );



        if(is_array(explode('/',$slug)) && count(explode('/',$slug)) > 1){


            add_rewrite_rule( $slug.'/(.+)/page/([0-9]{1,})/?$', 'index.php?post_type=imbaf_brands&category=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( $slug.'/(.+)/?$' , 'index.php?post_type=imbaf_brands&category=$matches[1]' , 'top' );

        }

        $slug = get_option('imbaf_types_slug');

        register_taxonomy(
            'imbaftypes',
            array('imbafproducts'),
            array(
                'label' => __('Typen',IMBAF_TEXT_DOMAIN),
                'labels' => array(
                    'name'               => __('Typen',IMBAF_TEXT_DOMAIN),
                    'singular_name'      => __('Typ',IMBAF_TEXT_DOMAIN),
                    'menu_name'          => __('Typen',IMBAF_TEXT_DOMAIN),
                    'name_admin_bar'     => __('Typen',IMBAF_TEXT_DOMAIN),
                    'add_new'            => __('Neuer Typ',IMBAF_TEXT_DOMAIN),
                    'add_new_item'       => __('Neuen Typ anlegen',IMBAF_TEXT_DOMAIN),
                    'edit_item'          => __('Typ bearbeiten',IMBAF_TEXT_DOMAIN),
                    'new_item'           => __('Neuer Typ',IMBAF_TEXT_DOMAIN),
                    'view_item'          => __('Typ ansehen',IMBAF_TEXT_DOMAIN),
                    'search_items'       => __('Typ suchen',IMBAF_TEXT_DOMAIN),
                    'not_found'          => __('Typ nicht gefunden',IMBAF_TEXT_DOMAIN),
                    'not_found_in_trash' => __("Keine Typen im Papierkorb",IMBAF_TEXT_DOMAIN),
                    'all_items'          => __("Alle Typen",IMBAF_TEXT_DOMAIN),
                    'archives' =>	    __("Produkte",IMBAF_TEXT_DOMAIN)

                ),
                'rewrite' => array( 'slug' => $slug, 'hierarchical' => true ),
                'show_ui' => true,
                'public' => get_option( 'imbaf_enable_product_pages' ),
                'hierarchical' => true
            )
        );


        if(is_array(explode('/',$slug)) && count(explode('/',$slug)) > 1) {
            add_rewrite_rule( get_option( 'imbaf_types_slug' ) . '/(.+)/page/([0-9]{1,})/?$', 'index.php?post_type=imbaftypes&category=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( get_option( 'imbaf_types_slug' ) . '/(.+)/?$', 'index.php?post_type=imbaftypes&category=$matches[1]', 'top' );
        }


        $slug = get_option('imbaf_tags_slug');

        register_taxonomy(
            'imbaftags',
            array('imbafproducts'),
            array(
                'label' => __('Tags',IMBAF_TEXT_DOMAIN),
                'rewrite' => array( 'slug' => get_option('imbaf_tags_slug') ),
                'hierarchical' => false,
                'show_ui' => true,
                'public' => get_option( 'imbaf_enable_product_pages' )
            )
        );

        if(is_array(explode('/',$slug)) && count(explode('/',$slug)) > 1) {
            add_rewrite_rule( $slug . '/(.+)/page/([0-9]{1,})/?$', 'index.php?post_type=imbaftags&category=$matches[1]&paged=$matches[2]', 'top' );
            add_rewrite_rule( $slug . '/(.+)/?$', 'index.php?post_type=imbaftags&category=$matches[1]', 'top' );
        }

        register_taxonomy(
            'imbafproperties',
            array('imbafproducts'),
            array(
                'label' => __('Vergleichswerte',IMBAF_TEXT_DOMAIN),
                'hierarchical' => false,
                'show_in_nav_menus' => false,
                'meta_box_cb' => false,
                'public' => false,
                'show_ui' => true
            )
        );

    }

}