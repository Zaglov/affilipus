<?php

namespace imbaa\Affilipus\Admin\Taxonomy;


class imbafproperties{


    var $property_types = null;


    function __construct(){


        add_action( 'edited_imbafproperties', array($this,'imbaf_properties_save'), 10, 1 );
        add_action( 'create_imbafproperties', array($this,'imbaf_properties_save'), 10, 1 );
        add_action( 'imbafproperties_add_form_fields', array($this,'imbaf_properties_create_add_fields'), 10, 2 );
        add_action( 'imbafproperties_edit_form_fields', array($this,'imbaf_properties_edit_add_fields'), 10, 2 );
        add_action( 'wp_ajax_imbaf_property_gen_get_list', array($this,'imbaf_properties_list') );


        $this -> property_types = $this -> get_property_types();


    }

    function get_property_types(){

        $property_types = [


            ['value' => 'bool', 'label' => __('Ja/Nein', 'imb_affiliate')],
            ['value' => 'number', 'label' => __('Zahl', 'imb_affiliate')],
            ['value' => 'text', 'label' => __('Freitext', 'imb_affiliate')],
            ['value' => 'rating', 'label' => __('Sternebewertung', 'imb_affiliate')],
            ['value' => 'features', 'label' => __('Produktfeatures', 'imb_affiliate')],
            ['value' => 'fromto', 'label' => __('Von/Bis Wert', 'imb_affiliate')],
            ['value' => 'grade', 'label' => __('Testergebnis', 'imb_affiliate')],

        ];



        return $property_types;



    }

    function imbaf_properties_create_add_fields(){


        // this will add the custom meta field to the add new term page
        ?>
        <div class="form-field">
            <label for="term_meta[imbaf_property_type]">Typ</label>

            <select name="term_meta[imbaf_property_type]">

                <?php

                foreach($this -> property_types as $property){

                    ?> <option style="width:100%;" value="<?php echo $property['value']; ?>"><?php echo $property['label']; ?></option> <?php

                }


                ?>
            </select>

            <p class="description"><?php _e('Vergleichswert-Typ','imb_affiliate'); ?></p>



            <label for="term_meta[imbaf_property_icon]">Icon</label>
            <input type="text" name="term_meta[imbaf_property_icon]">

            <p class="description"><?php _e('Font Awesome Icon','imb_affiliate'); ?></p>


            <label for="term_meta[imbaf_property_prefix]"><?php _e('Präfix','imb_affiliate'); ?></label>
            <input type="text" name="term_meta[imbaf_property_prefix]">

            <p class="description"><?php _e('Vorgestellter Wert (ca.)','imb_affiliate'); ?></p>

            <label for="term_meta[imbaf_property_suffix]"><?php _e('Suffix','imb_affiliate'); ?></label>
            <input type="text" name="term_meta[imbaf_property_suffix]">
            <p class="description"><?php _e('Nachgestellter Wert (z.B. kg, MhZ etc.)','imb_affiliate'); ?></p>



        </div>
        <?php


    }

    function imbaf_properties_edit_add_fields($term){


        $t_id = $term->term_id;

        $term_meta = get_option( "taxonomy_$t_id" ); ?>

        <tr class="form-field">

            <th scope="row" valign="top"><label for="term_meta[imbaf_property_icon]"><?php _e('Font Awesome Icon','imb_affiliate'); ?></label></th>
            <td>



                <input type="text" name="term_meta[imbaf_property_icon]" value="<?php if(isset($term_meta['imbaf_property_icon'])){echo esc_attr( $term_meta['imbaf_property_icon'] ); }?>">

                <p class="description"><?php _e('z.B. fa-bullhorn','imb_affiliate'); ?></p>


            </td>


        </tr>


        <tr class="form-field">

            <th scope="row" valign="top"><label for="term_meta[imbaf_property_suffix]"><?php _e('Präfix','imb_affiliate'); ?></label></th>
            <td>



                <input type="text" name="term_meta[imbaf_property_prefix]" value="<?php if(isset($term_meta['imbaf_property_prefix'])){echo esc_attr( $term_meta['imbaf_property_prefix'] ); }?>">

                <p class="description"><?php _e('Vorgestellter Wert (ca.)','imb_affiliate'); ?></p>


            </td>


        </tr>


        <tr class="form-field">
            <th scope="row" valign="top"><label for="term_meta[custom_term_meta]"><?php _e('Typ','imb_affiliate'); ?></label></th>
            <td>


                <select name="term_meta[imbaf_property_type]" class="widefat">


                    <?php

                    foreach($this -> property_types as $property){

                        ?> <option value="<?php echo $property['value']; ?>" <?php if($property['value'] == esc_attr( $term_meta['imbaf_property_type'] )){echo 'selected';} ?>><?php echo $property['label']; ?></option> <?php

                    }


                    ?>

                </select>

                <p class="description"><?php _e('Vergleichswert-Typ','imb_affiliate'); ?></p>
            </td>
        </tr>


        <tr class="form-field">

            <th scope="row" valign="top"><label for="term_meta[imbaf_property_suffix]"><?php _e('Suffix','imb_affiliate'); ?></label></th>
            <td>

                <input type="text" name="term_meta[imbaf_property_suffix]" value="<?php echo esc_attr( $term_meta['imbaf_property_suffix'] ); ?>">

                <p class="description"><?php _e('Nachgestellter Wert (z.B. kg, MhZ etc.)','imb_affiliate'); ?></p>

            </td>


        </tr>

        <?php


    }

    function imbaf_properties_save($term_id){

        if ( isset( $_POST['term_meta'] ) ) {
            $t_id = $term_id;
            $term_meta = get_option( "taxonomy_$t_id" );

            $cat_keys = array_keys( $_POST['term_meta'] );

            foreach ( $cat_keys as $key ) {
                if ( isset ( $_POST['term_meta'][$key] ) ) {
                    $term_meta[$key] = $_POST['term_meta'][$key];
                }
            }

            // Save the option array.
            update_option( "taxonomy_$t_id", $term_meta );
        }
    }

    function imbaf_properties_list(){


        header('Content-type: text/json');


        $custom_properties = \get_post_meta($_POST['post_id'],'_imbaf_custom_property_values',true);
        $terms = get_terms( 'imbafproperties', array( 'hide_empty' => false ) );


        foreach ( $terms as $key => &$term ) {

            if(@array_key_exists($term->slug,$custom_properties)){


                if($custom_properties[$term->slug]['value'] == 'false'){

                    $custom_properties[$term->slug]['value'] = false;

                }

                $term -> value = $custom_properties[$term->slug]['value'];
                $term -> value2 = $custom_properties[$term->slug]['value2'];
                $term -> position =$custom_properties[$term->slug]['position'];
                $term -> selected = true;

            } else {

                $term -> selected = false;
                $term -> value = null;
                $term -> value2 = null;
                $term -> position = $term->term_id;

                wp_remove_object_terms($_POST['post_id'],$term->name,'imbafproperties');

            }

            $term->meta = get_option( "taxonomy_$term->term_id" );

        }

        usort($terms,function($a,$b){

            return $a->position-$b->position;

        });

        $data = array('properties'=>array_values($terms));

        echo json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

        wp_die();



    }


}