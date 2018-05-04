<?php

namespace imbaa\Affilipus\Core\Widgets;
use imbaa\Affilipus\Core\Affiliates as Affiliates;
use imbaa\Affilipus\Core\Output as Output;


class buyWidget extends \WP_Widget {

    /**
     * Sets up the widgets name etc
     */
    public function __construct() {

        $widget_ops = array(
            'classname' => 'imbaf_product_recommendation',
            'description' => 'Affilipus: Produktempfehlung (Alpha)',
        );


        parent::__construct( 'imbaf_product_recommendation', 'Affilipus: Produktempfehlung', $widget_ops );

    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {


        $default_template = 'widget_product_recommentation';

        $p = new Affiliates\affiliateProduct();
        $s = new Output\imbafShortcodes();

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
        }



        $data = array('product' => $p -> loadProductById($instance['product']));

        $s -> sideload_stylesheet($default_template,'imbaf-'.$default_template);

        //$s -> sideload_stylesheet($args['template'],'imbaf-custom'.$args['template']);


        wp_enqueue_style('imbaf-'.$default_template);
        //wp_enqueue_style('imbaf-custom-'.$args['template']);

        $output = $s -> sideload_template('widget_product_recommentation','widget_product_recommentation', $instance, $data);


        echo $output;

        echo $args['after_widget'];

    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {

        $product = ! empty( $instance['product'] ) ? $instance['product'] : '';
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $display_review_button = ! empty( $instance['display_review_button'] ) ? $instance['display_review_button'] : 0;
        $review_button_target = ! empty( $instance['review_button_target'] ) ? $instance['review_button_target'] : '_self';
        $review_button_icon = ! empty( $instance['review_button_icon'] ) ? $instance['review_button_icon'] : null;
        $review_button_text = ! empty( $instance['review_button_text'] ) ? $instance['review_button_text'] : 'Zur Review';

        $display_buy_button = ! empty( $instance['display_buy_button'] ) ? $instance['display_buy_button'] : 1;
        $buy_button_icon = ! empty( $instance['buy_button_icon'] ) ? $instance['buy_button_icon'] : null;
        $buy_button_text = ! empty( $instance['buy_button_text'] ) ? $instance['buy_button_text'] : 'Jetzt kaufen';

        $display_widget_border = ! empty( $instance['display_widget_border'] ) ? $instance['display_widget_border'] : '';

        $title_link = ! empty( $instance['title_link'] ) ? $instance['title_link'] : 'product_page';
        $title_link_target = ! empty( $instance['title_link_target'] ) ? $instance['title_link_target'] : '_self';
        $title_link_rel = ! empty( $instance['title_link_rel'] ) ? $instance['title_link_rel'] : 'nofollow';


        $price_link = ! empty( $instance['price_link'] ) ? $instance['price_link'] : 'none';
        $price_link_target = ! empty( $instance['price_link_target'] ) ? $instance['price_link_target'] : '_blank';
        $price_link_rel = ! empty( $instance['price_link_rel'] ) ? $instance['price_link_rel'] : 'nofollow';

        $prime_link = ! empty( $instance['prime_link'] ) ? $instance['prime_link'] : 'none';
        $prime_link_target = ! empty( $instance['prime_link_target'] ) ? $instance['prime_link_target'] : '_blank';
        $prime_link_rel = ! empty( $instance['prime_link_rel'] ) ? $instance['prime_link_rel'] : 'nofollow';


        $p = new Affiliates\affiliateProduct();
        $products = $p -> listPublishedProducts();

        if(count($products) > 0){

        ?>

        <p>

            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titel:' ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $title; ?>">

        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'product' ); ?>"><?php _e( 'Produkt:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'product' ); ?>"
                    name="<?php echo $this->get_field_name( 'product' ); ?>">


                <?php


                foreach ( $products as $p ) {

                    echo '<option value="' . $p->ID . '"';

                    if ( esc_attr( $product ) == $p->ID ) {

                        echo " selected ";

                    }

                    echo '>' . $p->post_title . '</option>';

                }


                ?>

            </select>


        </p>

        <h3>Buttons</h3>

        <h4>Kaufbutton</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'display_buy_button' ); ?>"><?php _e( 'Buy Button anzeigen:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'display_buy_button' ); ?>"
                    name="<?php echo $this->get_field_name( 'display_buy_button' ); ?>">


                <option value="yes" <?php if($display_buy_button == 1){echo 'selected';} ?>>anzeigen</option>
                <option value="no" <?php if($display_buy_button == 0){echo 'selected';} ?>>verbergen</option>


            </select>


        </p>

        <p>

            <label for="<?php echo $this->get_field_id( 'buy_button_text' ); ?>"><?php _e( 'Kaufbutton Text:' ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'buy_button_text' ); ?>"
                   name="<?php echo $this->get_field_name( 'buy_button_text' ); ?>" value="<?php echo $buy_button_text; ?>" placeholder="Jetzt kaufen">

        </p>

        <p>

            <label for="<?php echo $this->get_field_id( 'buy_button_icon' ); ?>"><?php _e( 'Kaufbutton Icon:' ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'buy_button_icon' ); ?>"
                   name="<?php echo $this->get_field_name( 'buy_button_icon' ); ?>" value="<?php echo $buy_button_icon; ?>" placeholder="fa-shopping-cart">

        </p>

        <h4>Review Button</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'display_review_button' ); ?>"><?php _e( 'Review Button anzeigen:' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'display_review_button' ); ?>"
                    name="<?php echo $this->get_field_name( 'display_review_button' ); ?>">


                <option value="yes" <?php if($display_review_button == 1){echo 'selected';} ?>>anzeigen</option>
                <option value="no" <?php if($display_review_button == 0){echo 'selected';} ?>>verbergen</option>


            </select>


        </p>

        <p>

            <label for="<?php echo $this->get_field_id( 'review_button_text' ); ?>"><?php _e( 'Review Button Beschriftung:' ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'review_button_text' ); ?>"
                   name="<?php echo $this->get_field_name( 'review_button_text' ); ?>" value="<?php echo $review_button_text; ?>" placeholder="Zur Review">

        </p>

        <p>

            <label for="<?php echo $this->get_field_id( 'review_button_icon' ); ?>"><?php _e( 'Review Button Icon:' ); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id( 'review_button_icon' ); ?>"
                   name="<?php echo $this->get_field_name( 'review_button_icon' ); ?>" value="<?php echo $review_button_icon; ?>" placeholder="">

        </p>

        <h3>Verlinkung</h3>

        <h4>Buttons</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'review_button_target' ); ?>"><?php _e( 'Review Button target:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'review_button_target' ); ?>"
                    name="<?php echo $this->get_field_name( 'review_button_target' ); ?>">


                <option value="_self" <?php if($review_button_target == '_self'){echo 'selected';} ?>>_self</option>
                <option value="_blank" <?php if($review_button_target == '_blank'){echo 'selected';} ?>>_blank</option>


            </select>


        </p>

        <h4>Titel</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><?php _e( 'Titel Link:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'title_link' ); ?>"
                    name="<?php echo $this->get_field_name( 'title_link' ); ?>">


                <option value="product_page" <?php if($title_link == 'product_page'){echo 'selected';} ?>>Produktseite</option>
                <option value="buy_page" <?php if($title_link == 'buy_page'){echo 'selected';} ?>>Partnerseite</option>
                <option value="none" <?php if($title_link == 'none'){echo 'selected';} ?>>Keine Verlinkung</option>


            </select>


        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'title_link_target' ); ?>"><?php _e( 'Titel Linkziel:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'title_link_target' ); ?>"
                    name="<?php echo $this->get_field_name( 'title_link_target' ); ?>">


                <option value="_self" <?php if($title_link_target == '_self'){echo 'selected';} ?>>Gleiches Fenster</option>
                <option value="_blank" <?php if($title_link_target == '_blank'){echo 'selected';} ?>>Neues Fenster</option>


            </select>
            
        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'title_link_rel' ); ?>"><?php _e( 'Titel Link Follow:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'title_link_rel' ); ?>"
                    name="<?php echo $this->get_field_name( 'title_link_rel' ); ?>">


                <option value="follow" <?php if($title_link_rel == 'follow'){echo 'selected';} ?>>follow</option>
                <option value="nofollow" <?php if($title_link_rel == 'nofollow'){echo 'selected';} ?>>nofollow</option>


            </select>

        </p>

        <h4>Prime Logo</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'title_link' ); ?>"><?php _e( 'Prime Link:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'prime_link' ); ?>"
                    name="<?php echo $this->get_field_name( 'prime_link' ); ?>">


                <option value="product_page" <?php if($prime_link == 'product_page'){echo 'selected';} ?>>Produktseite</option>
                <option value="buy_page" <?php if($prime_link == 'buy_page'){echo 'selected';} ?>>Partnerseite</option>
                <option value="none" <?php if($prime_link == 'none'){echo 'selected';} ?>>Keine Verlinkung</option>


            </select>


        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'prime_link_target' ); ?>"><?php _e( 'Prime Linkziel:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'prime_link_target' ); ?>"
                    name="<?php echo $this->get_field_name( 'prime_link_target' ); ?>">


                <option value="_self" <?php if($prime_link_target == '_self'){echo 'selected';} ?>>Gleiches Fenster</option>
                <option value="_blank" <?php if($prime_link_target == '_blank'){echo 'selected';} ?>>Neues Fenster</option>


            </select>

        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'prime_link_rel' ); ?>"><?php _e( 'Prime Link Follow:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'prime_link_rel' ); ?>"
                    name="<?php echo $this->get_field_name( 'prime_link_rel' ); ?>">


                <option value="follow" <?php if($prime_link_rel == 'follow'){echo 'selected';} ?>>follow</option>
                <option value="nofollow" <?php if($prime_link_rel == 'nofollow'){echo 'selected';} ?>>nofollow</option>


            </select>

        </p>


        <h4>Preis</h4>

        <p>


            <label for="<?php echo $this->get_field_id( 'price_link' ); ?>"><?php _e( 'Preis Link:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'price_link' ); ?>"
                    name="<?php echo $this->get_field_name( 'price_link' ); ?>">


                <option value="product_page" <?php if($price_link == 'product_page'){echo 'selected';} ?>>Produktseite</option>
                <option value="buy_page" <?php if($price_link == 'buy_page'){echo 'selected';} ?>>Partnerseite</option>
                <option value="none" <?php if($price_link == 'none'){echo 'selected';} ?>>Keine Verlinkung</option>


            </select>


        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'price_link_target' ); ?>"><?php _e( 'Preis Linkziel:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'price_link_target' ); ?>"
                    name="<?php echo $this->get_field_name( 'price_link_target' ); ?>">


                <option value="_self" <?php if($price_link_target == '_self'){echo 'selected';} ?>>Gleiches Fenster</option>
                <option value="_blank" <?php if($price_link_target == '_blank'){echo 'selected';} ?>>Neues Fenster</option>


            </select>

        </p>

        <p>


            <label for="<?php echo $this->get_field_id( 'price_link_rel' ); ?>"><?php _e( 'Preis Link Follow:' ); ?></label>


            <select class="widefat" id="<?php echo $this->get_field_id( 'price_link_rel' ); ?>"
                    name="<?php echo $this->get_field_name( 'price_link_rel' ); ?>">


                <option value="follow" <?php if($price_link_rel == 'follow'){echo 'selected';} ?>>follow</option>
                <option value="nofollow" <?php if($price_link_rel == 'nofollow'){echo 'selected';} ?>>nofollow</option>


            </select>

        </p>

        <h3>Optik</h3>

        <p>


            <label for="<?php echo $this->get_field_id( 'display_widget_border' ); ?>"><?php _e( 'Rahmen anzeigen:' ); ?></label>

            <select class="widefat" id="<?php echo $this->get_field_id( 'display_widget_border' ); ?>"
                    name="<?php echo $this->get_field_name( 'display_widget_border' ); ?>">


                <option value="yes" <?php if($display_widget_border == 1){echo 'selected';} ?>>anzeigen</option>
                <option value="no" <?php if($display_widget_border == 0){echo 'selected';} ?>>verbergen</option>


            </select>


        </p>

        <?php

        } else {

        ?>

        <p>Das Widget funktioniert nur mit veröffentlichten Produkten.<br>Derzeit hast du leider keine Produkte veröffentlicht.</p>

        <?php

        }

    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */

    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['product'] = ( ! empty( $new_instance['product'] ) ) ? strip_tags( $new_instance['product'] ) : '';
        $instance['display_review_button'] = ( ! empty( $new_instance['display_review_button'] ) ) ? strip_tags( $new_instance['display_review_button'] ) : '';
        $instance['review_button_target'] = ( ! empty( $new_instance['review_button_target'] ) ) ? strip_tags( $new_instance['review_button_target'] ) : '';
        $instance['review_button_icon'] = ( ! empty( $new_instance['review_button_icon'] ) ) ? strip_tags( $new_instance['review_button_icon'] ) : null;
        $instance['review_button_text'] = ( ! empty( $new_instance['review_button_text'] ) ) ? strip_tags( $new_instance['review_button_text'] ) : 'Zur Review';

        $instance['display_buy_button'] = ( ! empty( $new_instance['display_buy_button'] ) ) ? strip_tags( $new_instance['display_buy_button'] ) : '';
        $instance['buy_button_icon'] = ( ! empty( $new_instance['buy_button_icon'] ) ) ? strip_tags( $new_instance['buy_button_icon'] ) : null;
        $instance['buy_button_text'] = ( ! empty( $new_instance['buy_button_text'] ) ) ? strip_tags( $new_instance['buy_button_text'] ) : 'Jetzt kaufen';

        $instance['display_widget_border'] = ( ! empty( $new_instance['display_widget_border'] ) ) ? strip_tags( $new_instance['display_widget_border'] ) : 'no';

        $instance['title_link'] = ( ! empty( $new_instance['title_link'] ) ) ? strip_tags( $new_instance['title_link'] ) : 'none';
        $instance['title_link_target'] = ( ! empty( $new_instance['title_link_target'] ) ) ? strip_tags( $new_instance['title_link_target'] ) : '_blank';
        $instance['title_link_rel'] = ( ! empty( $new_instance['title_link_rel'] ) ) ? strip_tags( $new_instance['title_link_rel'] ) : 'follow';


        $instance['price_link'] = ( ! empty( $new_instance['price_link'] ) ) ? strip_tags( $new_instance['price_link'] ) : 'none';
        $instance['price_link_target'] = ( ! empty( $new_instance['price_link_target'] ) ) ? strip_tags( $new_instance['price_link_target'] ) : '_blank';
        $instance['price_link_rel'] = ( ! empty( $new_instance['price_link_rel'] ) ) ? strip_tags( $new_instance['price_link_rel'] ) : 'nofollow';

        $instance['prime_link'] = ( ! empty( $new_instance['prime_link'] ) ) ? strip_tags( $new_instance['prime_link'] ) : 'none';
        $instance['prime_link_target'] = ( ! empty( $new_instance['prime_link_target'] ) ) ? strip_tags( $new_instance['prime_link_target'] ) : '_blank';
        $instance['prime_link_rel'] = ( ! empty( $new_instance['prime_link_rel'] ) ) ? strip_tags( $new_instance['prime_link_rel'] ) : 'nofollow';



        foreach($instance as $key => &$value){


            if($value == 'yes'){$value = true;}
            else if($value == 'no') {$value = false;}


        }

        return $instance;

    }
}

// Register and load the widget


function imbaf_load_widgets() {
    register_widget( 'imbaa\Affilipus\Core\Widgets\buyWidget' );
}

add_action( 'widgets_init', 'imbaa\Affilipus\Core\Widgets\imbaf_load_widgets' );