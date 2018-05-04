<?php

namespace imbaa\Affilipus\Core\Filter;
use imbaa\Affilipus\Core as CORE;


class Comments {


    function __construct(){


        if ( get_option( 'imbaf_execute_shortcodes_in_comments' ) ) {
            add_filter( 'comments_template', array( $this, 'comment_filter' ), 500);
        }

    }

    function comment_filter($content){


        global $shortcode_tags;

        $enabled_shortcodes = array();

        $shortcodes = CORE\Output\imbafShortcodeDefinitions::$shortcodes;


        foreach($shortcodes as $shortcode => $details){


            array_push($enabled_shortcodes,$shortcode);
            array_push($enabled_shortcodes,$details['alias']);

        }

        foreach ($shortcode_tags as $tag => $func)
        {
            if (!in_array($tag, $enabled_shortcodes))
            {
                //remove_shortcode($tag);
            }
        }

        add_filter('comment_text',array($this,'filter_shortcodes'));
        add_filter('comment_text', 'do_shortcode');

    }


    function filter_shortcodes($content){

        global $shortcode_tags;

        $enabled_shortcodes = array();
        $shortcodes = CORE\Output\imbafShortcodeDefinitions::$shortcodes;


        foreach($shortcodes as $shortcode => $details){


            array_push($enabled_shortcodes,$shortcode);
            array_push($enabled_shortcodes,$details['alias']);

        }

        preg_match_all( '/' . get_shortcode_regex() . '/', $content, $matches, PREG_SET_ORDER );

        foreach ($shortcode_tags as $tag => $func)
        {
            if (!in_array($tag, $enabled_shortcodes))
            {




                if ( empty( $matches ) ) {
                    //return false;
                } else {



                    foreach ( $matches as $shortcode ) {

                        if ( $tag === $shortcode[2] ) {

                            $content = str_replace($shortcode[0],' ',$content);


                        } elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {


                        }

                    }

                }




            }
        }





        /*echo "<pre>";

       print_r($content);

        echo "</pre>";*/



        return $content;






    }



}