<?php

namespace imbaa\Affilipus\Core\Utilities;

if ( ! defined( 'ABSPATH' ) ) { die( 'Forbidden' ); }

class Utilities{



    public static function translate_prices($prices){



        $currency_symbols = array(

            "EUR" => "€",
            "USD" => "US-$",
            "GBP" => "£"

        );

        $display_names = array(

            'list_price' => __('Listenpreis',IMBAF_TEXT_DOMAIN),
            'offering_price' => __('Angebotspreis',IMBAF_TEXT_DOMAIN),
            'lowest_new_price' => __('Niedrigster Neupreis',IMBAF_TEXT_DOMAIN),
            'lowest_used_price' => __('Niedrigster Gebrauchtpreis',IMBAF_TEXT_DOMAIN)

        );


        foreach($prices as &$price){

            if(isset($price['name'])){
                $price['display_name'] = $display_names[$price['name']];
            }

            if(array_key_exists($price['currency'],$currency_symbols)){

                $price['currency_symbol'] = $currency_symbols[$price['currency']];

            } else {
                $price['currency_symbol'] = $price['currency'];
            }


        }





        return $prices;




    }

}