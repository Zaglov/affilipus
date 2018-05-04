<?php

namespace imbaa\Affilipus\Core\Shortcodes;
use imbaa\Affilipus\Entities\AffilipusProduct;


class AffilipusRaw {



    public static function init(){

        $definition = self::getDefinition();


        add_shortcode($definition['code'],[__CLASS__,'render']);
        add_shortcode($definition['alias'],[__CLASS__,'render']);


    }


    public static function getDefinition(){

        $definition = array(
            'code'        => 'affilipus_raw',
            'alias'       => 'afp_raw',
            'callback'    => 'raw',
            'hr_name'     => 'Affilipus RAW',
            'description' => 'Rohausgabe für das API-Debugging',
            'templates'   => false,
            'generator'   => false,
            'public'    => false
        );


        return $definition;

    }


    public static function getParameters(){

        return [

            'product' => null,
            'asin' => null,
            'term' => null

        ];

    }

    public static function render($args=[],$content=null){

        $args = shortcode_atts(self::getParameters(), $args, 'affilipus_raw' );


        ob_start();

        if($args['product'] != null){

            $product = new AffilipusProduct((int) $args['product']);

        } else if ($args['asin'] != null){

            $product = [];

        }

        echo "<pre style='background-color:#333; color:#fff; padding: 15px; clear:both;'>";

        print_r($args);

        echo "</pre>";

        echo "<pre style='background-color:#333; color:#fff; padding: 15px; clear:both;'>";

        print_r($product);

        echo "</pre>";


        $output = ob_get_clean();


        return $output;

    }

}