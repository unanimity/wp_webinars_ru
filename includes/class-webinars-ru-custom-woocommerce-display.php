<?php
 
class Webinars_Ru_Custom_WooCommerce_Display {
 
    private $textfield_id;
 
    public function __construct() {
        $this->textfield_id = 'webinars_ru_text_field';
    }
 
    public function init() {
 echo "Step 009";
        add_action(
            'woocommerce_product_thumbnails',
            array( $this, 'webinars_ru_product_thumbnails' )
        );
    }
 
    public function webinars_ru_product_thumbnails() {
     echo "Steo 756gdfg7";
        $teaser = get_post_meta( get_the_ID(), $this->textfield_id, true );
        if ( empty( $teaser ) ) {
            return;
        }
 
        echo esc_html( $teaser );
    }
}