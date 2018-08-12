<?php
 
class Webinars_Ru_Custom_WooCommerce_Field {
 
        private $textfield_id;
 
    public function __construct() {
        $this->textfield_id = 'webinars_ru_text_field';
        
                echo "<script>console.log( 'Debug Objects: " . "Webinars_Ru_Custom_WooCommerce_Field  __construct" . "' );</script>";
    }
 
    public function init() {
 
            add_action(
                'woocommerce_product_options_grouping',
                array( $this, 'webinars_ru_product_options_grouping' )
            );
             echo "<script>console.log( 'Debug Objects: " . "Webinars_Ru_Custom_WooCommerce_Field  init" . "' );</script>";
    }
 
    public function webinars_ru_product_options_grouping() {
  
            $description = sanitize_text_field( 'Enter a description that will be displayed for those who are viewing the product.' );
            $placeholder = sanitize_text_field( 'Tease your product with a short description.' );
        echo "<script>console.log( 'Debug Objects: " . "product_options_grouping" . "' );</script>";
            $args = array(
                'id'            => $this->textfield_id,
                'label'         => sanitize_text_field( 'Product Teaser' ),
                'placeholder'   => 'Tease your product with a short description',
                'desc_tip'      => true,
                'description'   => $description,
            );
    
            woocommerce_wp_text_input( $args );
    }
}