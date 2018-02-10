<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://ltsharma.com
 * @since      1.0.0
 *
 * @package    Gooplac
 * @subpackage Gooplac/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gooplac
 * @subpackage Gooplac/admin
 * @author     ltsharma <ltsharma24@gmail.com>
 */
class Gooplac_Admin {

	private $plugin_name;

	private $version;

	public $default_selector;

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->default_selector = "#gooplac-input";

	}

	public function register_settings_page() {

		add_menu_page(                             // parent slug
			__( 'Google Place Autocomplete - GOOPLAC', 'bnspress' ),      // page title
			__( 'Gooplac', 'gooplac' ),      // menu title
			'manage_options',                        // capability
			'gooplac',                           // menu_slug
			array( $this, 'display_settings_page' ),  // callable function
			'dashicons-location-alt'
		);
	}

	public function display_settings_page() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/gooplac-admin-display.php';

	}

	public function register_settings(){
		// Here we are going to register our setting.

		register_setting(
		  $this->plugin_name . '-settings',
		  $this->plugin_name . '-settings',
		  array( $this, 'sandbox_register_setting' )
		);

		// Here we are going to add a section for our setting.
		add_settings_section(
		  $this->plugin_name . '-settings-section',
		  __( 'Settings', 'bnspress' ),
		  array( $this, 'sandbox_add_settings_section' ),
		  $this->plugin_name . '-settings'
		);

		add_settings_field(
		  'gooplac-g-key',
		  __( 'Google API Key', 'gooplac-g-key' ),
		  array( $this, 'sandbox_add_settings_field_input_text' ),
		  $this->plugin_name . '-settings',
		  $this->plugin_name . '-settings-section',
		  array(
		    'label_for' => 'gooplac-g-key',
		    'default'   => __( '','gooplac-g-key' ),
		    'description' => __( '<p class="description">Google API key <a target="_blank" href="https://support.google.com/googleapi/answer/6158862">Get API key</a>, (<a target="_blank" href="https://developers.google.com/places/web-service/get-api-key">Know more</a>).</p>', 'gooplac-g-key' )
		  )
		);

		add_settings_field(
		  'gooplac-input-selectors',
		  __( 'Input selectors (Comma Seperated)', 'gooplac-input-selectors' ),
		  array( $this, 'sandbox_add_settings_field_input_text' ),
		  $this->plugin_name . '-settings',
		  $this->plugin_name . '-settings-section',
		  array(
		    'label_for' => 'gooplac-input-selectors',
		    'default'   => __( '','gooplac-input-selectors' ),
		    'description' => __( '<p class="description">The selector of the input field where google places autocomplete should be attached (Eg, #address, .home-address)', 'gooplac-input-selectors</p>' )
		  )
		);

		$type_options = array('geocode','address','establishment' );

		add_settings_field(
		  'complete-type-selectors',
		  __( 'Type of data', 'complete-type-selectors' ),
		  array( $this, 'sandbox_add_settings_field_input_select' ),
		  $this->plugin_name . '-settings',
		  $this->plugin_name . '-settings-section',
		  array(
		    'label_for' => 'complete-type-selectors',
		    'options'   => __( $type_options,'complete-type-selectors'),
		    'description' => __( '<p class="description">Type of autocomplete data to be shown</p>' )
		  )
		);

		$options = get_option( $this->plugin_name . '-settings' );
		$options['default-selector'] = $this->default_selector;
		update_option( $this->plugin_name . '-settings',$options,null );

	}

	public function sandbox_register_setting( $input ) {

		$new_input = array();
		if ( isset( $input ) ) {
			// Loop trough each input and sanitize the value if the input id isn't post-types
			foreach ( $input as $key => $value ) {
				if ( $key == 'post-types' ) {
					$new_input[ $key ] = $value;
				} else {
					$new_input[ $key ] = sanitize_text_field( $value );
				}
			}
		}

		return $new_input;

	}


	public function sandbox_add_settings_section() {

		return;

	}

	public function sandbox_add_settings_field_input_text( $args ) {

		$field_id = $args['label_for'];
		$field_default = $args['default'];
		$field_description = $args['description'];
		$options = get_option( $this->plugin_name . '-settings' );

		$option = $field_default;

		if ( ! empty( $options[ $field_id ] ) ) {

			$option = $options[ $field_id ];

		}

		?>

			<input type="text" name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" value="<?php echo esc_attr( $option ); ?>" class="regular-text" />
					<?php echo ( $field_description ); ?>

		<?php

	}

	public function sandbox_add_settings_field_input_select( $args ) {

		$field_id = $args['label_for'];
		$field_options = $args['options'];
		$field_description = $args['description'];
		$options = get_option( $this->plugin_name . '-settings' );

		if ( ! empty( $options[ $field_id ] ) ) {

			$option = $options[ $field_id ];

		}

		?>

			<select name="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" id="<?php echo $this->plugin_name . '-settings[' . $field_id . ']'; ?>" class="regular-text" />

				<?php foreach ($field_options as $key => $value) { ?>
					
					<option value="<?=$value?>" <?= !empty($option)?(($value === $option)?'selected':''):'' ?>><?=ucfirst($value)?></option>

			<?php 	} ?>

			</sclect>
					<?php echo ( $field_description ); ?>

		<?php

	}

}
