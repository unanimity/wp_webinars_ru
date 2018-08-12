<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Webinars_RU
 * @subpackage Webinars_RU/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Webinars_RU
 * @subpackage Webinars_RU/admin
 * @author     Sergei Kharlamov unanimity@list.ru
 */
class Webinars_RU_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $webinars_ru    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $webinars_ru       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

	}



}




add_action('admin_menu', 'add_webinars_ru_plugin_page');

function add_webinars_ru_plugin_page(){
	add_options_page( 'Настройки Webinars_ru', 'Webinars Ru', 'manage_options', 'webinars_ru_slug', 'webinars_ru_options_page_output' );
}


function webinars_ru_options_page_output(){
     ?>


    <div class="wrap">
		<h2><?php echo get_admin_page_title() ?></h2>

		<form action="options.php" method="POST">
			<?php
				settings_fields( 'webinar_ru_options' );     // скрытые защитные поля
				do_settings_sections( 'webinar_ru_settings' ); // секции с настройками (опциями). У нас она всего одна 'section_id'
				submit_button();
		 
			
 
			?>
		</form>
		<?php
		wbnr_get_company_events_f(null,null);

		?>
		
	</div>
    


    <script>

    </script>
    <!-- The result of the search will be rendered inside this div -->
 
    <?php
}


add_action( 'woocommerce_order_status_completed', 'webinar_ru_order_completed', 10, 1);



add_action('admin_init', 'webinar_ru_settings');
function webinar_ru_settings(){  
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'webinar_ru_options', 'api_key', 'sanitize_callback' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'webinar_ru_section_id', 'Основные настройки:', '', 'webinar_ru_settings' ); 

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('api_key_id', 'Ключь API ', 'webinar_ru_api_key', 'webinar_ru_settings', 'webinar_ru_section_id' );
//	add_settings_field('primer_field2', 'Другая опция', 'fill_primer_field2', 'webinar_ru_settings', 'webinar_ru_section_id' );



}

add_action(
    'woocommerce_process_product_meta',
    array( $this, 'add_custom_linked_field_save' )
);
 function add_custom_linked_field_save( $post_id ) {
 
        if ( ! ( isset( $_POST['woocommerce_meta_nonce'], $_POST[ $this->textfield_id ] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
        return false;
    }
 
    $product_teaser = sanitize_text_field(
        wp_unslash( $_POST[ $this->textfield_id ] )
    );
 
    update_post_meta(
        $post_id,
        $this->textfield_id,
        esc_attr( $product_teaser )
    );
}



## Заполняем опцию 1
function webinar_ru_api_key(){
	$val = get_option('api_key');
	$val = $val ? $val['input'] : null;
 
	?>
	<input type="text" name="api_key[input]" value="<?php echo esc_attr( $val ) ?>" />
	<?php
}

## Заполняем опцию 2
function fill_primer_field2(){
	$val = get_option('option_name');
	$val = $val ? $val['checkbox'] : null;
	?>
	<label><input type="checkbox" name="option_name[checkbox]" value="1" <?php checked( 1, $val ) ?> /> отметить</label>
	<?php
}

## Очистка данных
function sanitize_callback( $options ){ 
	// очищаем
	foreach( $options as $name => & $val ){
		if( $name == 'input' )
			$val = strip_tags( $val );

	 
	}
 
	//die(print_r( $options )); // Array ( [input] => aaaa [checkbox] => 1 )

	return $options;
}


function wbnr_get_company_events_f( $atts, $content = null)	{
    if (! empty($atts)){
	extract( shortcode_atts( array(
				'message' => ''
			), $atts 
		) 
	);}
    	$val = get_option('api_key');
    	$val = $val ? $val['input'] : null;
    	
    	
    	/* Получаем список уже имеющихся товаров*/
    	$WebinarsID=array();
	  $args = array(
                'post_type'      => 'product',
                'posts_per_page' => 10,
                'product_cat'    => 'Webinars_RU'
            );

            $loop = new WP_Query( $args );
        
            while ( $loop->have_posts() ) : $loop->the_post();
                global $product;
 
            $value= get_post_meta(get_the_ID(), '_webinars_ru_id', true );
             array_push($WebinarsID,$value);
                echo '<br /><a href="'.get_permalink().'">' .' ('.' '.  $value  .')  name ='.get_the_title().'</a>';
            endwhile;
        
            wp_reset_query();
            
        /* Запрос на список вебинаров*/
            
		 $request = wp_remote_get( 'https://userapi.webinar.ru/v3/organization/events/schedule?to=2018-08-11&perPage=500',
             array( 
            'timeout' => 50,
            'headers' => array( 
                                'X-Auth-Token' => $val,
                                'CMS-Wordpress'=>'1.0'
                                ) 
             
             )
             );
             

            if( is_wp_error( $request ) ) {
            	return false; // Bail early
            }
            
            $body = wp_remote_retrieve_body( $request );
            $data = json_decode( $body );
            
            if( ! empty( $data ) ) {
                echo '<h1>Список вебинаров:</h1>';
            	echo '<style type="text/css">
                        .tftable {font-size:12px;color:#333333;width:100%;border-width: 1px;border-color: #729ea5;border-collapse: collapse;}
                        .tftable th {font-size:12px;background-color:#acc8cc;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;text-align:left;}
                        .tftable tr {background-color:#d4e3e5;}
                        .tftable td {font-size:12px;border-width: 1px;padding: 8px;border-style: solid;border-color: #729ea5;}
                        .tftable tr:hover {background-color:#ffffff;}
                        </style>
                        <table class="tftable" border="1">
                        <tr><th>ID</th><th>Название</th><th>Статус</th><th>Начало</th><th>Конец</th><th>Дни недели</th></tr>

';
            	foreach ( $data as $event ) {
                    
                    if (!in_array($event->id,$WebinarsID,true))  {
                        wbnr_add_woocomerce_product($event->id,$event->name,"","6000");
                        echo ' <tr style="color: #F410DD;"><td>'.$event->id.'</td><td>'.$event->name.'</td><td>'.$event->status.'</td><td>'.$event->estimatedAt.'</td><td>'.$event->endsAt.'</td><td>'.$event->rule.'</td></tr>  ';
               
                        
                    } else
                    
                {
                    echo ' <tr style="color: #ff5151;"><td>'.$event->id.'</td><td>'.$event->name.'</td><td>'.$event->status.'</td><td>'.$event->estimatedAt.'</td><td>'.$event->endsAt.'</td><td>'.$event->rule.'</td></tr>  ';
                }
                /*	foreach ( $event->eventSessions as $eventSessions ) {
                    
                	echo ' <tr><td>'.$eventSessions->id.'</td><td>'.$eventSessions->name.'</td><td>'.$eventSessions->status.'</td><td>'.$eventSessions->startsAt.'</td><td>'.(($eventSessions->endsAt)? $eventSessions->endsAt : '-') .'</td></tr>  ';
                	
                     }*/
                }
            	echo '</table>';
            	
              
            }
               wp_reset_query();
            
            
	//return $message . ' ' . $content;
 
}

function wbnr_add_woocomerce_product($webinars_id,$i_title, $i_discription, $i_price)
{
        $post = array(
        'post_author' => $user_id,
        'post_content' => $i_discription,
        'post_status' => "publish",
        'post_title' => $i_title,
        'post_parent' => '',
        'post_type' => "product",
    );
    
    //Create post
    $post_id = wp_insert_post( $post, $wp_error );
    if($post_id){
        $attach_id = get_post_meta($product->parent_id, "_thumbnail_id", true);
        add_post_meta($post_id, '_thumbnail_id', $attach_id);
    }
    
    wp_set_object_terms( $post_id, 'Webinars_RU', 'product_cat' );
    wp_set_object_terms($post_id, 'simple', 'product_type');
    
    update_post_meta( $post_id, '_visibility', 'visible' );
    update_post_meta( $post_id, '_stock_status', 'instock');
    update_post_meta( $post_id, 'total_sales', '0');
    update_post_meta( $post_id, '_downloadable', 'yes');
    update_post_meta( $post_id, '_virtual', 'yes');
    update_post_meta( $post_id, '_regular_price', "1" );
    update_post_meta( $post_id, '_sale_price', "1" );
    update_post_meta( $post_id, '_purchase_note', "" );
    update_post_meta( $post_id, '_featured', "no" );
    update_post_meta( $post_id, '_weight', "" );
    update_post_meta( $post_id, '_length', "" );
    update_post_meta( $post_id, '_width', "" );
    update_post_meta( $post_id, '_height', "" );
    update_post_meta($post_id, '_sku', "");
    update_post_meta( $post_id, '_product_attributes', array('webinars_ru_id'=>'aasfasf23ew3d'));
    update_post_meta( $post_id, '_sale_price_dates_from', "" );
    update_post_meta( $post_id, '_sale_price_dates_to', "" );
    update_post_meta( $post_id, '_price', $i_price );
    update_post_meta( $post_id, '_sold_individually', "" );
    update_post_meta( $post_id, '_manage_stock', "no" );
    update_post_meta( $post_id, '_backorders', "no" );
    update_post_meta( $post_id, '_stock', "" );
    
    // file paths will be stored in an array keyed off md5(file path)
    $downdloadArray =array('name'=>"Test", 'file' => $uploadDIR['baseurl']."/video/".$video);
    
    $file_path =md5($uploadDIR['baseurl']."/video/".$video);
    
    
    $_file_paths[  $file_path  ] = $downdloadArray;
    // grant permission to any newly added files on any existing orders for this product
    // do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
    update_post_meta( $post_id, '_downloadable_files', $_file_paths);
    update_post_meta( $post_id, '_download_limit', '');
    update_post_meta( $post_id, '_download_expiry', '');
    update_post_meta( $post_id, '_download_type', '');
      update_post_meta( $post_id, '_webinars_ru_id', $webinars_id);
    
    
    update_post_meta( $post_id, '_product_image_gallery', '');
    
}
 function webinar_ru_order_completed($order_id)
 {
     debug_to_console( "webinar_ru_order_completed".$order_id );
     
 }
 function debug_to_console( $data ) {
if ( is_array( $data ) )
 $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
 else
 $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";
echo $output;
}
debug_to_console( "Test" );