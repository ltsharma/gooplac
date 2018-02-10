<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ltsharma.com
 * @since      1.0.0
 *
 * @package    Gooplac
 * @subpackage Gooplac/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gooplac
 * @subpackage Gooplac/public
 * @author     ltsharma <ltsharma24@gmail.com>
 */
class Gooplac_Public {
	private $plugin_name;

	private $version;
	private $settings;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = get_option( 'gooplac-settings', false );;

	}

	/************* Auto-complete location *******************/ 
	public function include_google_places_api() {

		$api_key = $this->settings['gooplac-g-key'];

	    //register google maps api if not already registered
	    if ( !wp_script_is( 'google-maps', 'registered' ) ) {
	        wp_register_script( 'google-maps', 
	        'http://maps.googleapis.com/maps/api/js?libraries=places&key='.$api_key, 
	        array( 'jquery' ), 
	        false );
	    }
	    //enqueue google maps api if not already enqueued
	    if ( !wp_script_is( 'google-maps', 'enqueued' ) ) {
	        wp_enqueue_script( 'google-maps' );
	    }  
	}
			
	public function google_address_autocomplete() {

			$sInputs = $this->settings['gooplac-input-selectors'];
			$sInputs .= ','.$this->settings['default-selector']; 
			$aInputs = explode(',', $sInputs);
			$sType = $this->settings['complete-type-selectors'];
			?>
			<script>
			
			jQuery(document).ready(function($) {
				//Array of input fields ID. 

				var gacFields = <?php echo json_encode($aInputs) ?>;
			
				$.each( gacFields, function( key, field ) {
					var input = document.querySelector(field);
					//varify the field
					if ( input != null ) {
						
						var options = {
							types: ['<?=$sType?>'],
						};
						 
						var autocomplete = new google.maps.places.Autocomplete(input, options);
						 
						autocomplete.addListener( 'place_changed', function(e) {
					
							var place = autocomplete.getPlace();
					
							if (!place.geometry) {
								return;
							}
						});
					}
				});
			});
		    </script>
		    <?php 
		} 


}
