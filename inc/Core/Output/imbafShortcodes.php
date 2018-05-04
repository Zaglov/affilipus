<?php

/**
 * 
 * Letztes Glied der Shortcode Kette - hier läuft alles zusammen
 * 
 */


namespace imbaa\Affilipus\Core\Output;
use imbaa\Affilipus\Core as CORE;


class imbafShortcodes extends imbafShortcodesHTML
{
    
    public $post_id = null;
    public $product = null;

    function __construct($init = false){




        if($init == true){


            $api = new \imbaa\Affilipus\Core\API\affilipusAPI();



            if(!$api->allowAction()){


                foreach($this::$shortcodes as $code => $info){

                    add_shortcode($code, array($this, 'foo'));
                    add_shortcode($info['alias'], array($this, 'foo'));

                }


            } else {


                foreach($this::$shortcodes as $code => $info){

                    add_shortcode($code, array($this, $info['callback']));
                    add_shortcode($info['alias'], array($this, $info['callback']));

                }


            }




            add_action( 'wp_enqueue_scripts', array($this,'register_plugin_styles') );

        }

    }


    function foo(){


        return '';

    }

    /*Interne Shortcodes*/

    function shortcode_table(){

        $codes = $this::$shortcodes;
        $s = new CORE\Output\imbafShortcodes();

        $output = '<table class="imbaf_shortcode_table">';

        $output .= '<thead>

                <tr>
                    <th>Shortcode</th>
                    <th>Beschreibung</th>
                </tr>

            </thead>';


        $output .= '<tbody>';


        $output .= '</tbody>';


        foreach($codes as $code) {


            if($code['public'] != false){

                $output .= '<tr>';

                if(isset($code['documentation_link'])){
                $output .= '<td class="key"><a href="'.$code['documentation_link'].'" title="Mehr zu '.$code['hr_name'].'">' . $code['hr_name']. '</a><br>['.$code['code'].']</td>';
                } else {
                $output .= '<td class="key">'.$code['hr_name']. '<br>['.$code['code'].']</td>';
                }
                $output .= '<td class="value">' . $code['description'] . '</td>
                            </tr>';

            }

        }

        $output .= '</table>';

        return $output;

    }

    function shortcode_info(){


        $code = $this::$shortcodes;
        $code = $code['affilipus_product_box'];
        $s = new CORE\Output\imbafShortcodes();

        $code['params'] = @$s->get_defaults($code);


        $output = '<p>'.$code['description'].'</p>';


        $output .= '<table class="imbaf_shortcode_table">';

        $output .= '<thead>';

        $output .= '<tr>
                    <th>Parameter</th>
                    <th>Beschreibung</th>
                    <th>Standardwert</th>
                    </tr>';

        $output .= '</thead>';

        $output .= '<tbody>';


        foreach($code['params'] as $param => $info){


            if($info['internal'] == false){


                if($info['value'] == null){$info['value'] = '-';}

                $output .= '<tr>
                            <td>'.$param.'</td>
                            <td>'.$info['description'].'</td>
                            <td>'.$info['value'].'</td>
                            </tr>';


            }

        }

        $output .= '</tbody>';

        $output .= '</table>';

        return $output;



    }

    /*HTML Shortcodes*/


    /* Debug Shortcodes */

    function raw($args){

        $default_args = array();

        if(isset($args['get_params'])){return $default_args;}

        $args = shortcode_atts($default_args, $args);
        $args = $this -> handle_true_false($args);

        $this -> prepare_data();

        ob_start();


        echo "<h2>RAW OUTPUT</h2>";
        echo "<pre>";

        print_r($this -> product);

        echo "</pre>";

        $output = ob_get_clean ();


        return $output;


    }

    /* Hilfsmethoden */

    function get_product_id(){


        global $post;


        if($post->post_type == 'imbafproducts'){

            return $post->ID;

        } else {

            return false;

        }


    }

    function product_missing(){

        return '<p>Das gewünschte Produkt konnte leider nicht gefunden werden.</p>';

    }


}
