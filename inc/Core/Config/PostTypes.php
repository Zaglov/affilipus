<?php

namespace imbaa\Affilipus\Core\Config;


class PostTypes {


    function __construct(){

        add_action( 'init', array($this,'register_post_types'));



    }

    function register_post_types(){

        if(get_transient('_imbaf_slugs_changed') == 'yes'){



            flush_rewrite_rules();
            delete_transient('_imbaf_slugs_changed');

        }

        //echo "POST TYPES";

        $current_user = wp_get_current_user();
        if(in_array('administrator',$current_user->roles)){

            $show_ui = true;

        } else {
            $show_ui = false;
        }

        /* Register the post type. */
        register_post_type(
            'imbafproducts', // Post type name. Max of 20 characters. Uppercase and spaces not allowed.
            array(

                'public' => true,
                'labels' => array(

                    'name'               => __('Produkte',IMBAF_TEXT_DOMAIN),
                    'singular_name'      => __('Produkt',IMBAF_TEXT_DOMAIN),
                    'menu_name'          => __('Produkte',IMBAF_TEXT_DOMAIN),
                    'name_admin_bar'     => __('Produkte',IMBAF_TEXT_DOMAIN),
                    'add_new'            => __('Neues Produkt',IMBAF_TEXT_DOMAIN),
                    'add_new_item'       => __('Neues Produkt anlegen',IMBAF_TEXT_DOMAIN),
                    'edit_item'          => __('Produkt bearbeiten',IMBAF_TEXT_DOMAIN),
                    'new_item'           => __('Neues Produkt',IMBAF_TEXT_DOMAIN),
                    'view_item'          => __('Produkt ansehen',IMBAF_TEXT_DOMAIN),
                    'search_items'       => __('Produkt suchen',IMBAF_TEXT_DOMAIN),
                    'not_found'          => __('Produkt nicht gefunden',IMBAF_TEXT_DOMAIN),
                    'not_found_in_trash' => __("Keine Produkte im Papierkorb",IMBAF_TEXT_DOMAIN),
                    'all_items'          => __("Alle Produkte",IMBAF_TEXT_DOMAIN),

                    /* Labels for hierarchical post types only. */
                    //'parent_item'        => __( 'Parent Example',             'example-textdomain' ),
                    //'parent_item_colon'  => __( 'Parent Example:',            'example-textdomain' ),

                    /* Custom archive label.  Must filter 'post_type_archive_title' to use. */
                    'archive_title'      => __( 'Produkte', IMBAF_TEXT_DOMAIN ),

                ),

                'publicly_queryable' => get_option( 'imbaf_enable_product_pages' ),
                'show_ui' => $show_ui,
                'hierarchical' => true,
                'supports' => array(

                    /* Post titles ($post->post_title). */


                    'title',

                    /* Post content ($post->post_content). */
                    'editor',

                    /* Post excerpt ($post->post_excerpt). */
                    'excerpt',

                    /* Post author ($post->post_author). */
                    'author',

                    /* Featured images (the user's theme must support 'post-thumbnails'). */
                    'thumbnail',

                    /* Displays comments meta box.  If set, comments (any type) are allowed for the post. */
                    'comments',

                    /* Displays meta box to send trackbacks from the edit post screen. */
                    'trackbacks',

                    /* Displays the Custom Fields meta box. Post meta is supported regardless. */
                    'custom-fields',

                    /* Displays the Revisions meta box. If set, stores post revisions in the database. */
                    'revisions',

                    'page-attributes'

                    /* Displays the Format meta box and allows post formats to be used with the posts. */
                    //'post-formats'

                ),
                'capability_type' => 'page',
                'capabilities' => array(



                ),
                'menu_position' => 4,
                'menu_icon'           => 'dashicons-cart',
                'has_archive' => true,
                'rewrite' => array(
                    'slug'                => get_option('imbaf_products_slug'),
                    'with_front'          => false,
                    'hierarchical'        => false,
                    'pages'               => false,
                    'feeds'               => true

                )



            )
        );



    }

}