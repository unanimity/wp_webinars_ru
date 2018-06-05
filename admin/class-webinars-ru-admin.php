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
				
				
				
    	$val = get_option('api_key');
    	$val = $val ? $val['input'] : null;
	
		 $request = wp_remote_get( 'https://userapi.webinar.ru/v3/organization/members',
             array( 'timeout' => 10,
            'headers' => array( 'X-Auth-Token' => $val) 
             ) );
             
           //  echo $val;
            if( is_wp_error( $request ) ) {
            	return false; // Bail early
            }
            $body = wp_remote_retrieve_body( $request );
            $data = json_decode( $body );
            if( ! empty( $data ) ) {
            	
            	echo ' <h3>Admin Email:  '.$data[0]->email.'</h3> <img src="'.$data[0]->photo->thumbnails->{'640x1920'}.'"  >';
              
            }
			?>
		</form>
	</div>
    


    <script>

    </script>
    <!-- The result of the search will be rendered inside this div -->
 
    <?php
}

add_action('admin_init', 'webinar_ru_settings');
function webinar_ru_settings(){echo "-1";
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'webinar_ru_options', 'api_key', 'sanitize_callback' );

	// параметры: $id, $title, $callback, $page
	add_settings_section( 'webinar_ru_section_id', 'Основные настройки:', '', 'webinar_ru_settings' ); 

	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('api_key_id', 'Ключь API ', 'webinar_ru_api_key', 'webinar_ru_settings', 'webinar_ru_section_id' );
//	add_settings_field('primer_field2', 'Другая опция', 'fill_primer_field2', 'webinar_ru_settings', 'webinar_ru_section_id' );

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



