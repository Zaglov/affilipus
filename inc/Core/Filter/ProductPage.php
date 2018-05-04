<?php

namespace imbaa\Affilipus\Core\Filter;
use imbaa\Affilipus\Core as CORE;


class ProductPage {


    function __construct(){


        add_filter( 'the_content', array( $this, 'content_filter' ), 0, 1 );

    }


    // Dieser Filter sorgt dafür, dass das Template, das man sich im Backend „zusammenstellen" kann geladen wird, statt des default Theme Outputs.

    function content_filter($content){


        if(get_post_type(get_the_ID()) == 'imbafproducts'){


            $content = do_shortcode(get_option('imbaf_default_template'));

            $content = apply_filters('the_excerpt', $content);


        }

        return $content;





    }



}