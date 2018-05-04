<?php

/** Text Shortcodes */

namespace imbaa\Affilipus\Core\Output;
use imbaa\Affilipus\Core as CORE;


class imbafShortcodesText extends imbafShortcodeBasics {
    

    function review_rating_text($args,$content = ''){


        $default_args = array(
            'product' => array(
                'value' =>null,
                'description' => 'Produkt',
                'type' => 'product'
            ),
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $stars = get_post_meta($args['product'],'_imbaf_review_star_rating',true);


        return $stars;


    }

    function review_text($args,$content = ''){


        $default_args = array(
            'product' => array(
                'value' =>null,
                'description' => 'Produkt',
                'type' => 'product'
            ),
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        $text = get_post_meta($args['product'],'_imbaf_review_text',true);




        return $text;


    }

    function affilipus_reflink($args,$content = ''){


        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            ),
            'type' => array(
                'value' => 'product_page',
                'description' => 'Typ des Reflinks. Standard "product_page" - bei allen Produkten vorhanden.',
                'internal' => true
            ),
            'target' => array(
                'value' => '_blank',
                'description' => 'Linkziel, "_blank" zum Öffnen in einem neuen Fenster.'),
            'type'             => 'select',
            'options'          => array(
                array( 'option_text' => 'Neues Fenster', 'option_value' => '_blank' ),
                array( 'option_text' => 'Selbes Fenster', 'option_value' => '_self' )
            ),
            'title' => array(
                'value' => null,
                'description' => 'Linktitel')
        );


        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args) , $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }


        $this -> prepare_data();

        if(!$this->product){return $this -> product_missing();}

        if(isset($this->product['_imbaf_affiliate_links'][$args['type']])){

            $output = '<a href="'.$this->product['_imbaf_affiliate_links'][$args['type']]['url'].'" title="'.$args['title'].'" target="'.$args['target'].'" rel="nofollow">'.do_shortcode($content).'</a>';


        } else {

            $output = do_shortcode($content);
        }

        return $output;


    }

    function affilipus_price($args){


        $default_args = array(
            'product' => array(
                'value'=>null,
                'description' => 'Produkt',
                'type' => 'product'
            ),
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }
        $this -> prepare_data();

        if(!$this->product){return $this->product_missing();}

        return $this->product['_imbaf_display_price']['price'].$this->product['_imbaf_display_price']['currency_symbol'];

    }

    function affilipus_product_name($args,$content = ''){


        $default_args = array(
            'product' => array(
                'value' =>null,
                'description' => 'Produkt',
                'type' => 'product'
            ),
        );



        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){
            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();


        if(!$this->product){return $this->product_missing();}


        return $this -> product['product_name'];

    }

    function user_description($args){

        $default_args = array();

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts($default_args, $args);
        $args = $this -> handle_true_false($args);

        $content = get_the_content();
        $content = apply_filters('the_excerpt', $content);

        return $content;

    }

    function default_description($args){

        $default_args = array(
            'title' => array(
                'value' => null,
                'description' => 'Optionale H2-Überschrift'
            ),
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){
            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();


        $output = '';


        if($args['title'] != null){

            $output .= '<h2>'.$args['title'].'</h2>';

        }


        $output .= get_post_meta($this -> post_id,'_imbaf_description',true);


        return $output;

    }

    function item_size($args){

        $default_args = array(

            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);




        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();
        if(!$this -> product){return $this -> product_missing();}

        if(!isset($this->product['_imbaf_item_dimensions_width']) || !isset($this->product['_imbaf_item_dimensions_height']) || !isset($this->product['_imbaf_item_dimensions_length'])){return '';}
        if(!isset($this->product['_imbaf_item_dimensions_unit'])){$this->product['_imbaf_item_dimensions_unit'] = 'cm';}


        return $this->product['_imbaf_item_dimensions_width'].' x '.$this->product['_imbaf_item_dimensions_height'].' x '.$this->product['_imbaf_item_dimensions_length'].' '.$this->product['_imbaf_item_dimensions_unit'];


    }

    function item_weight($args){


        $default_args = array(

            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();

        if(!$this -> product){return $this -> product_missing();}

        if(!isset($this->product['_imbaf_item_weight'])){return '';}
        if(!isset($this->product['_imbaf_item_weight_unit'])){$this->product['_imbaf_item_weight_unit'] = 'kg';}

        return $this->product['_imbaf_item_weight'].' '.$this->product['_imbaf_item_weight_unit'];


    }

    function package_size($args){


        $default_args = array(

            'product' => array(
                'value' =>null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();

        if(!$this -> product){return $this -> product_missing();}

        if(!isset($this->product['_imbaf_package_dimensions_width']) || !isset($this->product['_imbaf_package_dimensions_height']) || !isset($this->product['_imbaf_package_dimensions_length'])){return '';}
        if(!isset($this->product['_imbaf_package_dimensions_unit'])){$this->product['_imbaf_package_dimensions_unit'] = 'cm';}


        return $this->product['_imbaf_package_dimensions_width'].' x '.$this->product['_imbaf_package_dimensions_height'].' x '.$this->product['_imbaf_package_dimensions_length'].' '.$this->product['_imbaf_package_dimensions_unit'];


    }

    function package_weight($args){


        $default_args = array(
            'product' => array(
                'value' => null,
                'description' => 'Produkt',
                'type' => 'product'
            )
        );

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts( $this->handle_defaults($default_args), $args);
        $args = $this -> handle_true_false($args);

        if($args['product'] != null){

            $this -> post_id = $args['product'];

        }

        $this -> prepare_data();

        if(!$this -> product){return $this -> product_missing();}

        if(!isset($this->product['_imbaf_package_weight'])){return '';}
        if(!isset($this->product['_imbaf_package_weight_unit'])){$this->product['_imbaf_package_weight_unit'] = 'kg';}


        return $this->product['_imbaf_package_weight'].' '.$this->product['_imbaf_package_weight_unit'];


    }

}
